<?php

namespace App\Http\Controllers;

use App\Models\AssetDocument;
use App\Models\MaintenancePhoto;
use App\Models\RepairRequestPhoto;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileDownloadController extends Controller
{
    /**
     * Download ticket attachment with authorization check.
     */
    public function downloadTicketAttachment(TicketAttachment $attachment): StreamedResponse
    {
        $ticket = $attachment->ticket;

        $this->authorize('view', $ticket);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found.');
        }

        return $this->streamFile(
            $attachment->file_path,
            $attachment->original_filename,
            $attachment->mime_type
        );
    }

    /**
     * Download asset document with authorization check.
     */
    public function downloadAssetDocument(AssetDocument $document): StreamedResponse
    {
        $this->authorize('view', $document->asset);

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return $this->streamFile(
            $document->file_path,
            $document->original_filename,
            $document->mime_type
        );
    }

    /**
     * Download maintenance photo with authorization check.
     */
    public function downloadMaintenancePhoto(MaintenancePhoto $photo): StreamedResponse
    {
        $task = $photo->task;

        $this->authorize('view', $task);

        if (!Storage::disk('public')->exists($photo->file_path)) {
            abort(404, 'File not found.');
        }

        return $this->streamFile(
            $photo->file_path,
            $photo->filename,
            $photo->mime_type ?? 'image/webp'
        );
    }

    /**
     * Download repair request photo (public access for requester).
     */
    public function downloadRepairRequestPhoto(RepairRequestPhoto $photo, string $requestNumber): StreamedResponse
    {
        $repairRequest = $photo->repairRequest;

        if ($repairRequest->request_number !== $requestNumber) {
            abort(404, 'Photo not found.');
        }

        if (!Storage::disk('public')->exists($photo->path)) {
            abort(404, 'File not found.');
        }

        return $this->streamFile(
            $photo->path,
            $photo->filename,
            $photo->mime_type ?? 'image/webp'
        );
    }

    /**
     * Stream file response with proper headers.
     */
    protected function streamFile(string $path, string $filename, string $mimeType): StreamedResponse
    {
        return response()->streamDownload(function () use ($path) {
            echo Storage::disk('public')->get($path);
        }, $filename, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
