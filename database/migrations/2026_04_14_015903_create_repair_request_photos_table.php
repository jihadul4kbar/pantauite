<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repair_request_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_request_id')->constrained('repair_requests')->cascadeOnDelete();
            $table->string('filename')->comment('Original filename');
            $table->string('path')->comment('Storage path of the compressed image');
            $table->string('mime_type')->default('image/webp');
            $table->integer('file_size')->comment('File size in bytes after compression');
            $table->integer('original_size')->comment('Original file size in bytes');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->timestamp('photo_taken_at')->nullable()->comment('Timestamp when photo was taken (EXIF)');
            $table->text('exif_data')->nullable()->comment('Full EXIF data as JSON');
            $table->timestamps();
            
            $table->index('repair_request_id');
            $table->index('photo_taken_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_request_photos');
    }
};
