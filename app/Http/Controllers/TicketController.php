<?php

namespace App\Http\Controllers;

use App\Enums\TicketPriority;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Department;
use App\Models\KbArticle;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use App\Services\SlaService;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TicketController extends Controller
{
    public function __construct(
        private TicketService $ticketService,
        private SlaService $slaService
    ) {}

    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Ticket::class);
        
        $user = Auth::user();

        // Build filters based on permissions
        $filters = [];

        if ($user->hasRole('it_staff')) {
            // IT Staff can see tickets assigned to them OR tickets they created
            $filters['assignee_id'] = $user->id;
            $filters['created_by_user'] = $user->id;
        } elseif ($user->hasPermission('view-own-tickets') && !$user->hasPermission('view-all-tickets')) {
            // End users see only their own tickets
            $filters['user_id'] = $user->id;
        }

        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        if ($request->filled('priority')) {
            $filters['priority'] = $request->priority;
        }

        if ($request->filled('category_id')) {
            $filters['category_id'] = $request->category_id;
        }

        if ($request->filled('assignee_id')) {
            $filters['assignee_id'] = $request->assignee_id;
        }

        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        // Get per_page from request, default to 10
        $perPage = $request->input('per_page', 10);

        $tickets = $this->ticketService->getPaginated($filters, $perPage);

        // Get filter options
        $categories = TicketCategory::active()->get();
        $departments = Department::active()->get();
        $itStaff = User::whereRole('it_staff', 'it_manager')->active()->get();

        return view('tickets.index', compact(
            'tickets',
            'categories',
            'departments',
            'itStaff',
            'filters'
        ));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $this->authorize('create', Ticket::class);

        $categories = TicketCategory::active()->get();
        $departments = Department::active()->get();
        $priorities = TicketPriority::cases();

        return view('tickets.create', compact(
            'categories',
            'departments',
            'priorities'
        ));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->ticketService->createTicket(
            $request->validated(),
            $request->user()
        );

        // Handle file attachments if any
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $this->attachFile($ticket, $file);
            }
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket ' . $ticket->ticket_number . ' created successfully.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        // Load relationships
        $ticket->load([
            'user',
            'assignee',
            'department',
            'category',
            'slaPolicy',
            'relatedKbArticle',
            'comments.user',
            'attachments.uploadedBy',
            'auditLogs.user',
            'repairRequest.photos',
        ]);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $categories = TicketCategory::active()->get();
        $departments = Department::active()->get();
        $priorities = TicketPriority::cases();
        $itStaff = User::whereRole('it_staff', 'it_manager')->active()->get();

        return view('tickets.edit', compact(
            'ticket',
            'categories',
            'departments',
            'priorities',
            'itStaff'
        ));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket = $this->ticketService->updateTicket(
            $ticket,
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $this->ticketService->deleteTicket($ticket, auth()->user());

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Assign ticket to user.
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorize('assign', $ticket);

        $validated = $request->validate([
            'assignee_id' => ['nullable', 'exists:users,id'],
        ]);

        $this->ticketService->assignTicket(
            $ticket,
            $validated['assignee_id'],
            $request->user()
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket assigned successfully.');
    }

    /**
     * Change ticket status.
     */
    public function changeStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:open,in_progress,resolved,closed,reopened'],
        ]);

        $this->authorize('changeStatus', $ticket);

        $this->ticketService->changeStatus(
            $ticket,
            $validated['status'],
            $request->user()
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket status updated to ' . ucfirst(str_replace('_', ' ', $validated['status'])) . '.');
    }

    /**
     * Link KB article to ticket.
     */
    public function linkKbArticle(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'kb_article_id' => ['nullable', 'exists:kb_articles,id'],
        ]);

        $ticket->update([
            'related_kb_article_id' => $validated['kb_article_id'] ?: null,
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', $validated['kb_article_id'] ? 'Knowledge Base article linked successfully.' : 'Knowledge Base article removed.');
    }

    /**
     * Add comment to ticket.
     */
    public function addComment(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string'],
            'is_internal' => ['sometimes', 'boolean'],
            'is_solution' => ['sometimes', 'boolean'],
            'attachments.*' => ['nullable', 'file', 'max:5120'],
        ]);

        $this->authorize('comment', $ticket);

        $comment = $this->ticketService->addComment(
            $ticket,
            $validated,
            $request->user()
        );

        // Mark first response if this is first comment by staff
        if (!$ticket->first_response_at && $request->user()->hasPermission('manage-tickets')) {
            $this->ticketService->markFirstResponse($ticket);
        }

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $this->attachFileToComment($ticket, $comment, $file);
            }
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Attach file to ticket.
     */
    protected function attachFile(Ticket $ticket, $file): void
    {
        $mimeType = $file->getMimeType();
        
        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'])) {
            $path = $this->processImageToWebp($file, 'tickets/' . $ticket->id);
            $mimeType = 'image/webp';
        } else {
            $path = $file->store('tickets/' . $ticket->id, 'public');
        }

        $ticket->attachments()->create([
            'filename' => basename($path),
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => Storage::disk('public')->size($path),
            'mime_type' => $mimeType,
            'uploaded_by' => auth()->id(),
        ]);
    }

    /**
     * Attach file to comment.
     */
    protected function attachFileToComment(Ticket $ticket, $comment, $file): void
    {
        $mimeType = $file->getMimeType();
        
        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'])) {
            $path = $this->processImageToWebp($file, 'tickets/' . $ticket->id . '/comments');
            $mimeType = 'image/webp';
        } else {
            $path = $file->store('tickets/' . $ticket->id . '/comments', 'public');
        }

        $ticket->attachments()->create([
            'comment_id' => $comment->id,
            'filename' => basename($path),
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => Storage::disk('public')->size($path),
            'mime_type' => $mimeType,
            'uploaded_by' => auth()->id(),
        ]);
    }

    /**
     * Process image and convert to WebP format
     */
    protected function processImageToWebp($file, string $directory): string
    {
        try {
            // Intervention Image 4.x API
            $manager = new ImageManager(Driver::class);
            $image = $manager->decode($file->getRealPath());

            $originalWidth = $image->width();
            $originalHeight = $image->height();

            if ($originalWidth > 1920 || $originalHeight > 1080) {
                $image->scale(width: 1920, height: 1080, upSize: false);
            }

            $filename = uniqid('ticket_') . '_' . time() . '.webp';
            $path = $directory . '/' . $filename;
            
            // Ensure directory exists
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            
            // Save as WebP format
            $image->save(Storage::disk('public')->path($path), 80, 'webp');

            return $path;
        } catch (\Exception $e) {
            \Log::error('Ticket image processing error: ' . $e->getMessage());
            // Fallback: store original file
            return $file->store($directory, 'public');
        }
    }

    /**
     * Toggle SLA pause/resume for a ticket.
     */
    public function toggleSlaPause(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $result = $this->slaService->toggleSlaPause($ticket);

        if ($result['success']) {
            $message = $result['action'] === 'paused'
                ? 'SLA timer paused successfully.'
                : 'SLA timer resumed successfully.';

            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', $message);
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('error', 'Failed to toggle SLA pause state.');
    }

    /**
     * Rate ticket satisfaction.
     */
    public function rateTicket(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $validated = $request->validate([
            'satisfaction_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'satisfaction_feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $ticket->update([
            'satisfaction_rating' => $validated['satisfaction_rating'],
            'satisfaction_feedback' => $validated['satisfaction_feedback'] ?? null,
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Thank you for your feedback!');
    }
}
