<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique(); // INV-2026-0001
            $table->foreignId('part_id')->constrained('inventory_parts')->cascadeOnDelete();
            $table->string('type'); // in/out/adjust/return
            $table->decimal('quantity', 10, 2);
            $table->decimal('quantity_before', 10, 2);
            $table->decimal('quantity_after', 10, 2);
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->foreignId('reference_id')->nullable(); // task_id, po_id, etc
            $table->string('reference_type')->nullable(); // App\Models\MaintenanceTask
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('supplier')->nullable();
            $table->text('notes')->nullable();
            $table->date('transaction_date');
            $table->timestamps();

            $table->index(['part_id', 'type']);
            $table->index(['transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
