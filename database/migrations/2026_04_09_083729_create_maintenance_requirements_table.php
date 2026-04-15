<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->nullable()->constrained('maintenance_tasks')->cascadeOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('maintenance_schedules')->cascadeOnDelete();
            $table->unsignedBigInteger('part_id')->nullable(); // Will be added later via separate migration
            $table->string('part_name');
            $table->string('part_number')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit')->default('pcs'); // pcs/liter/kg/box/set
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->string('supplier')->nullable();
            $table->boolean('is_consumable')->default(false);
            $table->integer('stock_used')->nullable(); // Actual stock deducted
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['task_id']);
            $table->index(['schedule_id']);
            $table->index(['part_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_requirements');
    }
};
