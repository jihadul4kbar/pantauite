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
        Schema::create('repair_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 50)->unique()->comment('format: REQ-YYYY-NNNN');
            
            // Requester Information (no login required)
            $table->string('requester_name')->comment('nama pengaju');
            $table->string('requester_email')->comment('email pengaju untuk notifikasi');
            $table->string('requester_phone')->nullable()->comment('nomor telepon pengaju');
            $table->string('requester_department')->nullable()->comment('departemen pengaju');
            
            // Request Details
            $table->string('subject')->comment('subjek permasalahan');
            $table->text('description')->comment('deskripsi detail permasalahan');
            $table->enum('priority', ['critical', 'high', 'medium', 'low'])->default('medium')->comment('prioritas perbaikan');
            $table->foreignId('category_id')->nullable()->constrained('ticket_categories')->nullOnDelete()->comment('kategori perbaikan');
            
            // Location & Asset Information
            $table->string('location')->nullable()->comment('lokasi perangkat/alat');
            $table->string('asset_name')->nullable()->comment('nama perangkat/alat yang bermasalah');
            $table->string('asset_serial')->nullable()->comment('nomor seri perangkat');
            
            // Status & Verification
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'converted'])->default('draft')->comment('status permintaan');
            $table->text('rejection_reason')->nullable()->comment('alasan ditolak');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->comment('IT Manager yang verifikasi');
            $table->timestamp('verified_at')->nullable()->comment('tanggal verifikasi');
            
            // After Conversion
            $table->foreignId('ticket_id')->nullable()->constrained('tickets')->nullOnDelete()->comment('ticket yang dibuat dari permintaan ini');
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('request_number');
            $table->index('status');
            $table->index('requester_email');
            $table->index('priority');
            $table->index('category_id');
            $table->index('created_at');
            $table->index(['status', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_requests');
    }
};
