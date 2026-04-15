<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('maintenance_type')->default('preventive'); // preventive/corrective/predictive
            $table->string('frequency_type'); // daily/weekly/monthly/yearly/custom
            $table->integer('frequency_value')->default(1);
            $table->date('next_due_date');
            $table->date('last_completed_date')->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->decimal('approval_threshold', 15, 2)->nullable(); // Cost requiring approval
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['asset_id', 'is_active']);
            $table->index(['next_due_date', 'is_active']);
            $table->index(['frequency_type', 'frequency_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
