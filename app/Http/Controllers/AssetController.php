<?php

namespace App\Http\Controllers;

use App\Enums\AssetStatus;
use App\Enums\AssetType;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Department;
use App\Models\User;
use App\Models\Vendor;
use App\Services\AssetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public function __construct(
        private AssetService $assetService
    ) {}

    /**
     * Display a listing of assets.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Asset::class);

        $filters = [];

        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        if ($request->filled('type')) {
            $filters['type'] = $request->type;
        }

        if ($request->filled('vendor_id')) {
            $filters['vendor_id'] = $request->vendor_id;
        }

        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        // Get per_page from request, default to 10
        $perPage = $request->input('per_page', 10);

        $assets = $this->assetService->getPaginated($filters, $perPage);

        // Get filter options
        $vendors = Vendor::active()->get();
        $departments = Department::active()->get();

        return view('assets.index', compact(
            'assets',
            'vendors',
            'departments',
            'filters'
        ));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        $this->authorize('create', Asset::class);

        $types = AssetType::cases();
        $statuses = AssetStatus::cases();
        $conditions = ['new', 'good', 'fair', 'poor', 'broken'];
        $depreciationMethods = ['straight_line', 'declining_balance', 'none'];
        $vendors = Vendor::active()->get();
        $departments = Department::active()->get();
        $users = User::active()->get();

        return view('assets.create', compact(
            'types',
            'statuses',
            'conditions',
            'depreciationMethods',
            'vendors',
            'departments',
            'users'
        ));
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(StoreAssetRequest $request)
    {
        $validated = $request->validated();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $validated['images'] = $this->uploadImages($request->file('images'));
        }

        $asset = $this->assetService->createAsset(
            $validated,
            $request->user()
        );

        return redirect()
            ->route('assets.show', $asset)
            ->with('success', 'Asset ' . $asset->asset_code . ' created successfully.');
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset)
    {
        $this->authorize('view', $asset);

        // Load relationships
        $asset->load([
            'vendor',
            'assignedUser',
            'assignedDepartment',
            'lifecycleLogs.user',
            'maintenanceLogs',
            'documents.uploadedBy',
            'tickets' => function ($query) {
                $query->with(['user', 'assignee', 'category'])
                      ->latest();
            },
        ]);

        // Calculate current depreciation
        $currentDepreciatedValue = $asset->calculateDepreciation();

        return view('assets.show', compact('asset', 'currentDepreciatedValue'));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset)
    {
        $this->authorize('update', $asset);

        $types = AssetType::cases();
        $statuses = AssetStatus::cases();
        $conditions = ['new', 'good', 'fair', 'poor', 'broken'];
        $depreciationMethods = ['straight_line', 'declining_balance', 'none'];
        $vendors = Vendor::active()->get();
        $departments = Department::active()->get();
        $users = User::active()->get();

        return view('assets.edit', compact(
            'asset',
            'types',
            'statuses',
            'conditions',
            'depreciationMethods',
            'vendors',
            'departments',
            'users'
        ));
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $validated = $request->validated();

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images if replace is requested
            if ($request->boolean('delete_old_images')) {
                $this->deleteImages($asset->images ?? []);
                $validated['images'] = [];
            }

            // Upload new images
            $existingImages = $asset->images ?? [];
            $newImages = $this->uploadImages($request->file('images'));
            $validated['images'] = array_merge($existingImages, $newImages);
        }

        $asset = $this->assetService->updateAsset(
            $asset,
            $validated,
            $request->user()
        );

        return redirect()
            ->route('assets.show', $asset)
            ->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);

        $this->assetService->deleteAsset($asset, auth()->user());

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset deleted successfully.');
    }

    /**
     * Assign asset to user
     */
    public function assign(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);

        $validated = $request->validate([
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
            'assigned_to_department_id' => ['nullable', 'exists:departments,id'],
            'assigned_notes' => ['nullable', 'string'],
        ]);

        if ($validated['assigned_to_user_id']) {
            $this->assetService->assignToUser(
                $asset,
                $validated['assigned_to_user_id'],
                $request->user()
            );
        } else {
            $this->assetService->assignToDepartment(
                $asset,
                $validated['assigned_to_department_id'] ?? null,
                $request->user()
            );
        }

        return redirect()
            ->route('assets.show', $asset)
            ->with('success', 'Asset assigned successfully.');
    }

    /**
     * Change asset status
     */
    public function changeStatus(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);

        $validated = $request->validate([
            'status' => ['required', 'in:procurement,inventory,deployed,maintenance,retired,disposed'],
            'reason' => ['nullable', 'string'],
        ]);

        $this->assetService->changeStatus(
            $asset,
            $validated['status'],
            $request->user(),
            $validated['reason'] ?? null
        );

        return redirect()
            ->route('assets.show', $asset)
            ->with('success', 'Asset status updated to ' . ucfirst($validated['status']) . '.');
    }

    /**
     * Log maintenance
     */
    public function logMaintenance(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);

        $validated = $request->validate([
            'description' => ['required', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'performed_by' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
        ]);

        $this->assetService->logMaintenance($asset, $validated, $request->user());

        return redirect()
            ->route('assets.show', $asset)
            ->with('success', 'Maintenance logged successfully.');
    }

    /**
     * Upload multiple images and return array of paths
     */
    protected function uploadImages($files): array
    {
        $uploadedPaths = [];

        // Ensure the directory exists
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('assets/images')) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('assets/images');
            \Illuminate\Support\Facades\Log::info("Directory 'assets/images' created on public disk.");
        }

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                try {
                    $path = $file->store('assets/images', 'public');
                    if ($path) {
                        $uploadedPaths[] = $path;
                        \Illuminate\Support\Facades\Log::info("Image uploaded successfully: {$path}");
                    } else {
                        \Illuminate\Support\Facades\Log::error("Failed to upload image: store() returned null");
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Image upload error: " . $e->getMessage());
                }
            } else {
                \Illuminate\Support\Facades\Log::warning("Invalid file skipped in uploadImages()");
            }
        }

        \Illuminate\Support\Facades\Log::info("Upload complete. Total images: " . count($uploadedPaths));

        return $uploadedPaths;
    }

    /**
     * Delete images from storage
     */
    protected function deleteImages(array $imagePaths): void
    {
        foreach ($imagePaths as $path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }
    }
}
