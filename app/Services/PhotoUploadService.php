<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * Photo Upload Service with WebP Compression
 */
class PhotoUploadService
{
    /**
     * Maximum file size before compression (2MB)
     */
    const MAX_FILE_SIZE = 2 * 1024 * 1024;

    /**
     * Maximum width for resized images
     */
    const MAX_WIDTH = 1920;

    /**
     * Maximum height for resized images
     */
    const MAX_HEIGHT = 1080;

    /**
     * WebP quality (0-100)
     */
    const WEBP_QUALITY = 80;

    /**
     * Upload and process a photo
     *
     * @return array Processed photo data
     */
    public static function upload(UploadedFile $file): array
    {
        $originalSize = $file->getSize();
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Generate unique filename
        $uniqueFilename = uniqid('repair_') . '_' . time() . '.webp';
        $path = 'repair-requests/photos/' . date('Y/m/d') . '/' . $uniqueFilename;

        // Read EXIF data if available
        $exifData = self::extractExifData($file);
        $photoTakenAt = $exifData['photo_taken_at'] ?? now();

        // Process and compress image
        $imageData = self::processImage($file, $path);

        return [
            'filename' => $originalFilename . '.webp',
            'path' => $path,
            'mime_type' => 'image/webp',
            'file_size' => $imageData['file_size'] ?? 0,
            'original_size' => $originalSize,
            'width' => $imageData['width'] ?? 0,
            'height' => $imageData['height'] ?? 0,
            'photo_taken_at' => $photoTakenAt,
            'exif_data' => $exifData['raw'] ?? null,
        ];
    }

    /**
     * Process and compress image to WebP
     */
    protected static function processImage(UploadedFile $file, string $path): array
    {
        try {
            // Ensure directory exists
            $directory = dirname($path);
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $manager = new ImageManager(Driver::class);
            $image = $manager->decode($file->getRealPath());

            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Resize if too large
            if ($originalWidth > self::MAX_WIDTH || $originalHeight > self::MAX_HEIGHT) {
                $image->scaleDown(width: self::MAX_WIDTH, height: self::MAX_HEIGHT);
            }

            // Save as WebP
            $image->save(Storage::disk('public')->path($path), self::WEBP_QUALITY, 'webp');

            return [
                'file_size' => Storage::disk('public')->size($path),
                'width' => $image->width(),
                'height' => $image->height(),
            ];
        } catch (\Exception $e) {
            \Log::error('Photo upload error: ' . $e->getMessage());
            // Fallback: store original file
            $fallbackPath = str_replace('.webp', '.jpg', $path);
            $file->storeAs(dirname($fallbackPath), basename($fallbackPath), 'public');
            
            return [
                'file_size' => Storage::disk('public')->size($fallbackPath),
                'width' => 0,
                'height' => 0,
            ];
        }
    }

    /**
     * Extract EXIF data from image
     */
    protected static function extractExifData(UploadedFile $file): array
    {
        $result = [
            'photo_taken_at' => now(),
            'raw' => null,
        ];

        // Check if file is JPEG (EXIF only works with JPEG)
        $mimeType = $file->getMimeType();
        if ($mimeType !== 'image/jpeg') {
            return $result;
        }

        // Try to read EXIF data
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($file->getRealPath());
            
            if ($exif) {
                $result['raw'] = json_encode($exif);

                // Extract DateTimeOriginal
                if (isset($exif['DateTimeOriginal'])) {
                    try {
                        // EXIF format: "2024:01:15 14:30:25"
                        $dateTime = str_replace(':', '-', substr($exif['DateTimeOriginal'], 0, 10)) . 
                                   substr($exif['DateTimeOriginal'], 10);
                        $result['photo_taken_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateTime);
                    } catch (\Exception $e) {
                        // Use current time if parsing fails
                    }
                } elseif (isset($exif['DateTime'])) {
                    try {
                        $dateTime = str_replace(':', '-', substr($exif['DateTime'], 0, 10)) . 
                                   substr($exif['DateTime'], 10);
                        $result['photo_taken_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateTime);
                    } catch (\Exception $e) {
                        // Use current time if parsing fails
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Delete a photo
     */
    public static function delete(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    /**
     * Validate uploaded file
     */
    public static function validate(UploadedFile $file): ?string
    {
        // Check file type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return 'File harus berupa gambar (JPG, PNG, atau WebP).';
        }

        // Check file size (max 10MB before compression)
        if ($file->getSize() > 10 * 1024 * 1024) {
            return 'Ukuran file maksimal 10MB.';
        }

        return null;
    }
}
