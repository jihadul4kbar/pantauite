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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->enum('maintenance_type', ['preventive', 'corrective', 'upgrade', 'inspection']);
            $table->string('title');
            $table->text('description');
            $table->foreignId('performed_by')->constrained('users')->restrictOnDelete()->comment('technician');
            $table->string('vendor_name')->nullable()->comment('external vendor jika ada');
            $table->decimal('cost', 15, 2)->nullable();
            $table->string('currency', 3)->default('IDR');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('completed');
            
            // Attachments
            $table->json('attachments')->nullable();
            
            // Result
            $table->text('outcome')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('next_maintenance_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('asset_id');
            $table->index('performed_by');
            $table->index('maintenance_type');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
            $table->index('next_maintenance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
