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
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    public function __construct(private AssetService $assetService) {}

    /**
     * Display a listing of assets.
     */
    public function index(Request $request)
    {
        $this->authorize("viewAny", Asset::class);

        $filters = [];

        if ($request->filled("status")) {
            $filters["status"] = $request->status;
        }

        if ($request->filled("type")) {
            $filters["type"] = $request->type;
        }

        if ($request->filled("vendor_id")) {
            $filters["vendor_id"] = $request->vendor_id;
        }

        if ($request->filled("search")) {
            $filters["search"] = $request->search;
        }

        // Get per_page from request, default to 10
        $perPage = $request->input("per_page", 10);

        $assets = $this->assetService->getPaginated($filters, $perPage);

        // Get filter options
        $vendors = Vendor::active()->get();
        $departments = Department::active()->get();

        return view(
            "assets.index",
            compact("assets", "vendors", "departments", "filters"),
        );
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        $this->authorize("create", Asset::class);

        $types = AssetType::cases();
        $statuses = AssetStatus::cases();
        $conditions = ["new", "good", "fair", "poor", "broken"];
        $depreciationMethods = ["straight_line", "declining_balance", "none"];
        $vendors = Vendor::active()->get();
        $departments = Department::active()->get();
        $users = User::active()->get();

        return view(
            "assets.create",
            compact(
                "types",
                "statuses",
                "conditions",
                "depreciationMethods",
                "vendors",
                "departments",
                "users",
            ),
        );
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(StoreAssetRequest $request)
    {
        // Debug logging
        \Log::info('Asset store request', [
            'has_files' => $request->hasFile('images'),
            'files_count' => $request->file('images') ? count($request->file('images')) : 0,
            'all_input' => $request->all(),
        ]);

        $validated = $request->validated();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $validated['images'] = $this->uploadImages($request->file('images'));
            \Log::info('Images uploaded', ['paths' => $validated['images']]);
        } else {
            \Log::warning('No images uploaded for asset');
        }

        $asset = $this->assetService->createAsset($validated, $request->user());

        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Asset created successfully',
                'redirect_url' => route('assets.show', $asset),
            ]);
        }

        return redirect()
            ->route('assets.show', $asset)
            ->with(
                'success',
                'Asset ' . $asset->asset_code . ' created successfully.',
            );
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset)
    {
        $this->authorize("view", $asset);

        // Load relationships
        $asset->load([
            "vendor",
            "assignedUser",
            "assignedDepartment",
            "lifecycleLogs.user",
            "maintenanceLogs",
            "documents.uploadedBy",
            "tickets" => function ($query) {
                $query->with(["user", "assignee", "category"])->latest();
            },
        ]);

        // Calculate current depreciation
        $currentDepreciatedValue = $asset->calculateDepreciation();

        return view("assets.show", compact("asset", "currentDepreciatedValue"));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset)
    {
        $this->authorize("update", $asset);

        $types = AssetType::cases();
        $statuses = AssetStatus::cases();
        $conditions = ["new", "good", "fair", "poor", "broken"];
        $depreciationMethods = ["straight_line", "declining_balance", "none"];
        $vendors = Vendor::active()->get();
        $departments = Department::active()->get();
        $users = User::active()->get();

        return view(
            "assets.edit",
            compact(
                "asset",
                "types",
                "statuses",
                "conditions",
                "depreciationMethods",
                "vendors",
                "departments",
                "users",
            ),
        );
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $validated = $request->validated();

        // Handle image uploads
        if ($request->hasFile("images")) {
            // Delete old images if replace is requested
            if ($request->boolean("delete_old_images")) {
                $this->deleteImages($asset->images ?? []);
                $validated["images"] = [];
            }

            // Upload new images
            $existingImages = $asset->images ?? [];
            $newImages = $this->uploadImages($request->file("images"));
            $validated["images"] = array_merge($existingImages, $newImages);
        }

        $asset = $this->assetService->updateAsset(
            $asset,
            $validated,
            $request->user(),
        );

        return redirect()
            ->route("assets.show", $asset)
            ->with("success", "Asset updated successfully.");
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Asset $asset)
    {
        $this->authorize("delete", $asset);

        $this->assetService->deleteAsset($asset, auth()->user());

        return redirect()
            ->route("assets.index")
            ->with("success", "Asset deleted successfully.");
    }

    /**
     * Assign asset to user
     */
    public function assign(Request $request, Asset $asset)
    {
        $this->authorize("update", $asset);

        $validated = $request->validate([
            "assigned_to_user_id" => ["nullable", "exists:users,id"],
            "assigned_to_department_id" => [
                "nullable",
                "exists:departments,id",
            ],
            "assigned_notes" => ["nullable", "string"],
        ]);

        if ($validated['assigned_to_user_id'] ?? null) {
            $this->assetService->assignToUser(
                $asset,
                $validated['assigned_to_user_id'],
                $request->user(),
            );
        } else {
            $this->assetService->assignToDepartment(
                $asset,
                $validated['assigned_to_department_id'] ?? null,
                $request->user(),
            );
        }

        return redirect()
            ->route("assets.show", $asset)
            ->with("success", "Asset assigned successfully.");
    }

    /**
     * Change asset status
     */
    public function changeStatus(Request $request, Asset $asset)
    {
        $this->authorize("update", $asset);

        $validated = $request->validate([
            "status" => [
                "required",
                "in:procurement,inventory,deployed,maintenance,retired,disposed",
            ],
            "reason" => ["nullable", "string"],
        ]);

        $this->assetService->changeStatus(
            $asset,
            $validated["status"],
            $request->user(),
            $validated["reason"] ?? null,
        );

        return redirect()
            ->route("assets.show", $asset)
            ->with(
                "success",
                "Asset status updated to " .
                    ucfirst($validated["status"]) .
                    ".",
            );
    }

    /**
     * Log maintenance
     */
    public function logMaintenance(Request $request, Asset $asset)
    {
        $this->authorize("update", $asset);

        $validated = $request->validate([
            "description" => ["required", "string"],
            "cost" => ["nullable", "numeric", "min:0"],
            "performed_by" => ["nullable", "string"],
            "date" => ["nullable", "date"],
            "vendor_id" => ["nullable", "exists:vendors,id"],
        ]);

        $this->assetService->logMaintenance(
            $asset,
            $validated,
            $request->user(),
        );

        return redirect()
            ->route("assets.show", $asset)
            ->with("success", "Maintenance logged successfully.");
    }

    /**
     * Upload multiple images and return array of paths
     */
    protected function uploadImages($files): array
    {
        $uploadedPaths = [];

        if (!Storage::disk('public')->exists('assets/images')) {
            Storage::disk('public')->makeDirectory('assets/images');
        }

        foreach ($files as $index => $file) {
            if (!$file) {
                Log::warning("File at index {$index} is null");
                continue;
            }
            
            if (!$file->isValid()) {
                Log::error("File at index {$index} is invalid. Error code: " . $file->getError());
                continue;
            }

            try {
                Log::info("Processing file: " . $file->getClientOriginalName() . ", size: " . $file->getSize() . ", mime: " . $file->getMimeType());
                
                $mimeType = $file->getMimeType();
                if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'])) {
                    $path = $this->processImageToWebp($file, 'assets/images');
                } else {
                    $path = $file->store('assets/images', 'public');
                }
                
                if ($path) {
                    Log::info("File uploaded successfully to: " . $path);
                    $uploadedPaths[] = $path;
                } else {
                    Log::error("File upload returned null path");
                }
            } catch (\Exception $e) {
                Log::error('Image upload error: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ':' . $e->getLine());
            }
        }

        Log::info("Total files uploaded: " . count($uploadedPaths));
        return $uploadedPaths;
    }

    /**
     * Process image and convert to WebP format
     */
    protected function processImageToWebp($file, string $directory): string
    {
        // Skip processing in testing environment
        if (app()->environment('testing')) {
            $filename = uniqid('asset_') . '_' . time() . '.webp';
            $path = $directory . '/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));
            return $path;
        }

        try {
            // Intervention Image 4.x API - use decode instead of read
            $manager = new \Intervention\Image\ImageManager(
                \Intervention\Image\Drivers\Gd\Driver::class
            );
            $image = $manager->decode($file->getRealPath());
            
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            if ($originalWidth > 1920 || $originalHeight > 1080) {
                $image->scale(width: 1920, height: 1080, upSize: false);
            }

            $filename = uniqid('asset_') . '_' . time() . '.webp';
            $path = $directory . '/' . $filename;
            
            // Save as WebP format
            $image->save(Storage::disk('public')->path($path), 80, 'webp');

            return $path;
        } catch (\Exception $e) {
            Log::error('Image processing error: ' . $e->getMessage());
            // Fallback: store original file
            return $file->store($directory, 'public');
        }
    }

    /**
     * Delete images from storage
     */
    protected function deleteImages(array $imagePaths): void
    {
        foreach ($imagePaths as $path) {
            \Illuminate\Support\Facades\Storage::disk("public")->delete($path);
        }
    }
}
