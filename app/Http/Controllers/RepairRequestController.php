<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRepairRequestRequest;
use App\Models\RepairRequest;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use App\Services\CaptchaService;
use App\Services\PhotoUploadService;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepairRequestController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private TicketService $ticketService
    ) {}

    /**
     * Show the public repair request form.
     */
    public function create()
    {
        $categories = TicketCategory::active()->get();
        $captcha = CaptchaService::generate();

        return view('repair-requests.create', compact('categories', 'captcha'));
    }

    /**
     * Store a new repair request (public form, no login required).
     */
    public function store(StoreRepairRequestRequest $request)
    {
        // Validate CAPTCHA first
        if (! CaptchaService::validate($request->captcha)) {
            return back()
                ->withInput()
                ->withErrors(['captcha' => CaptchaService::getErrorMessage()])
                ->with('captcha_error', true);
        }

        // Debug logging
        \Log::info('Repair request submit', [
            'has_files' => $request->hasFile('photos'),
            'files_count' => $request->file('photos') ? count($request->file('photos')) : 0,
        ]);

        // Validate photos if uploaded
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            $validationError = null;
            foreach ($request->file('photos') as $index => $photo) {
                \Log::info("Photo {$index}: " . $photo->getClientOriginalName() . ', size: ' . $photo->getSize() . ', mime: ' . $photo->getMimeType());
                
                $error = PhotoUploadService::validate($photo);
                if ($error) {
                    $validationError = $error;
                    \Log::error("Photo validation error: {$error}");
                    break;
                }
            }

            if ($validationError) {
                return back()
                    ->withInput()
                    ->withErrors(['photos' => $validationError]);
            }
        }

        DB::beginTransaction();

        try {
            $repairRequest = RepairRequest::create([
                'request_number' => $this->generateRequestNumber(),
                'requester_name' => $request->requester_name,
                'requester_email' => $request->requester_email,
                'requester_phone' => $request->requester_phone,
                'requester_department' => $request->requester_department,
                'subject' => $request->subject,
                'description' => $request->description,
                'priority' => $request->priority,
                'category_id' => $request->category_id,
                'location' => $request->location,
                'asset_name' => $request->asset_name,
                'asset_serial' => $request->asset_serial,
                'status' => 'submitted',
            ]);

            \Log::info('Repair request created', ['id' => $repairRequest->id, 'request_number' => $repairRequest->request_number]);

            // Process and save photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    \Log::info("Uploading photo {$index}...");
                    $photoData = PhotoUploadService::upload($photo);
                    \Log::info("Photo data: " . json_encode($photoData));
                    
                    $photo = $repairRequest->photos()->create($photoData);
                    \Log::info("Photo saved with ID: {$photo->id}, path: {$photo->path}");
                }
            }

            DB::commit();

            \Log::info('Repair request completed', ['id' => $repairRequest->id]);

            return redirect()
                ->route('repair-requests.success', $repairRequest->request_number)
                ->with('success', 'Permintaan perbaikan berhasil dikirim. Nomor permintaan Anda: '.$repairRequest->request_number);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Repair request failed: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat mengirim permintaan. Silakan coba lagi.');
        }
    }

    /**
     * Show success page after submitting repair request.
     */
    public function success($requestNumber)
    {
        $repairRequest = RepairRequest::where('request_number', $requestNumber)->firstOrFail();

        return view('repair-requests.success', compact('repairRequest'));
    }

    /**
     * Show all repair requests pending verification (IT Manager only).
     */
    public function index(Request $request)
    {
        // Authorization: Only IT Manager and Super Admin can access
        if (! auth()->user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets'])) {
            abort(403, 'Unauthorized action.');
        }

        $query = RepairRequest::with(['category', 'verifier', 'ticket'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        // Search by requester name, email, or subject
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('requester_name', 'like', "%{$search}%")
                    ->orWhere('requester_email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('request_number', 'like', "%{$search}%");
            });
        }

        $repairRequests = $query->paginate(20);
        $categories = TicketCategory::active()->get();

        return view('repair-requests.index', compact('repairRequests', 'categories'));
    }

    /**
     * Show a specific repair request for verification.
     */
    public function show(RepairRequest $repairRequest)
    {
        // Authorization
        if (! auth()->user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets'])) {
            abort(403, 'Unauthorized action.');
        }

        $repairRequest->load(['category', 'verifier', 'ticket', 'photos']);

        return view('repair-requests.show', compact('repairRequest'));
    }

    /**
     * Approve a repair request (IT Manager).
     */
    public function approve(RepairRequest $repairRequest, Request $request)
    {
        // Authorization
        if (! auth()->user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets'])) {
            abort(403, 'Unauthorized action.');
        }

        $repairRequest->approve(auth()->id());

        return redirect()
            ->route('repair-requests.admin.index')
            ->with('success', 'Permintaan perbaikan berhasil disetujui.');
    }

    /**
     * Reject a repair request (IT Manager).
     */
    public function reject(RepairRequest $repairRequest, Request $request)
    {
        // Authorization
        if (! auth()->user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $repairRequest->reject($request->rejection_reason, auth()->id());

        return redirect()
            ->route('repair-requests.admin.index')
            ->with('success', 'Permintaan perbaikan ditolak.');
    }

    /**
     * Delete a repair request (SUPER ADMIN ONLY).
     */
    public function destroy(RepairRequest $repairRequest)
    {
        // Cannot delete if already converted to ticket
        if ($repairRequest->isConverted()) {
            return back()->with('error', 'Tidak dapat menghapus permintaan yang sudah dikonversi menjadi tiket.');
        }

        $requestNumber = $repairRequest->request_number;
        $repairRequest->delete();

        return redirect()
            ->route('repair-requests.admin.index')
            ->with('success', "Permintaan perbaikan {$requestNumber} berhasil dihapus.");
    }

    /**
     * Convert an approved repair request to a ticket (IT Manager).
     */
    public function convertToTicket(RepairRequest $repairRequest)
    {
        // Authorization
        if (! auth()->user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets'])) {
            abort(403, 'Unauthorized action.');
        }

        // Can only convert if approved
        if (! $repairRequest->isApproved()) {
            return back()->with('error', 'Hanya permintaan yang disetujui yang dapat dikonversi menjadi tiket.');
        }

        DB::beginTransaction();

        try {
            // Find a default user for the ticket (IT Manager or first IT staff)
            $defaultUser = User::whereHas('role', function ($q) {
                $q->where('name', 'it_manager');
            })->first();

            if (! $defaultUser) {
                $defaultUser = User::whereHas('role', function ($q) {
                    $q->where('name', 'it_staff');
                })->first();
            }

            if (! $defaultUser) {
                throw new \Exception('Tidak ada user IT yang tersedia untuk membuat tiket.');
            }

            // Find department by name from requester_department
            $departmentId = null;
            if ($repairRequest->requester_department) {
                $department = \App\Models\Department::where('name', $repairRequest->requester_department)
                    ->orWhere('code', $repairRequest->requester_department)
                    ->first();
                
                if ($department) {
                    $departmentId = $department->id;
                }
            }

            // Create the ticket using TicketService with department as reporter
            $ticket = $this->ticketService->createTicket([
                'subject' => $repairRequest->subject,
                'description' => $repairRequest->description,
                'priority' => $repairRequest->priority,
                'category_id' => $repairRequest->category_id,
                'department_id' => $departmentId,
                'source' => 'web',
                'requester_name' => $repairRequest->requester_name,
                'requester_email' => $repairRequest->requester_email,
                'requester_department' => $repairRequest->requester_department,
            ], $defaultUser);

            // Mark repair request as converted
            $repairRequest->markAsConverted($ticket->id);

            DB::commit();

            return redirect()
                ->route('tickets.show', $ticket->id)
                ->with('success', 'Permintaan perbaikan berhasil dikonversi menjadi tiket.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengkonversi permintaan menjadi tiket: '.$e->getMessage());
        }
    }

    /**
     * Generate repair request number (REQ-YYYY-NNNN).
     */
    private function generateRequestNumber(): string
    {
        $year = now()->year;
        $lastRequest = RepairRequest::withTrashed()
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastRequest ? (intval(substr($lastRequest->request_number, -4)) + 1) : 1;

        return 'REQ-'.$year.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
