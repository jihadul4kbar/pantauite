<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_parts', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // electrical/mechanical/consumable/etc
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->string('supplier')->nullable();
            $table->string('location')->nullable(); // Storage location
            $table->decimal('quantity_in_stock', 10, 2)->default(0);
            $table->decimal('reorder_point', 10, 2)->default(0);
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->string('unit')->default('pcs'); // pcs/liter/kg/box/set
            $table->string('manufacturer')->nullable();
            $table->string('model_compatibility')->nullable(); // Compatible asset models
            $table->boolean('is_active')->default(true);
            $table->date('last_restocked')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['part_number']);
            $table->index(['category', 'is_active']);
            $table->index(['quantity_in_stock', 'reorder_point']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_parts');
    }
};
