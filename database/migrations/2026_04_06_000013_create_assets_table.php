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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code', 50)->unique()->comment('format: AST-{TYPE}-NNNN');
            $table->enum('asset_type', ['hardware', 'software', 'network']);
            
            // Basic Info
            $table->string('name');
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->string('part_number', 100)->nullable();
            
            // Specifications (flexible per type)
            $table->json('specs')->nullable()->comment('{cpu, ram, storage, os, etc}');
            
            // Status
            $table->enum('status', ['procurement', 'inventory', 'deployed', 'maintenance', 'retired', 'disposed'])->default('inventory');
            $table->enum('condition', ['new', 'good', 'fair', 'poor', 'broken'])->default('new');
            
            // Assignment
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->text('assigned_notes')->nullable();
            
            // Location
            $table->string('location')->nullable()->comment('building, floor, room');
            
            // Purchase Information
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('vendor_name')->nullable();
            $table->string('purchase_order_number', 100)->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('currency', 3)->default('IDR');
            
            // Warranty
            $table->date('warranty_start')->nullable();
            $table->date('warranty_end')->nullable();
            $table->string('warranty_provider')->nullable();
            $table->text('warranty_notes')->nullable();
            
            // Depreciation
            $table->enum('depreciation_method', ['straight_line', 'declining_balance', 'none'])->default('straight_line');
            $table->unsignedInteger('useful_life_years')->nullable();
            $table->decimal('depreciated_value', 15, 2)->nullable();
            $table->date('depreciation_start_date')->nullable();
            
            // End of Life
            $table->date('disposal_date')->nullable();
            $table->text('disposal_reason')->nullable();
            $table->decimal('disposal_value', 15, 2)->nullable();
            
            // Additional
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('asset_code');
            $table->index('asset_type');
            $table->index('status');
            $table->index('assigned_to_user_id');
            $table->index('assigned_to_department_id');
            $table->index('warranty_end');
            $table->index('condition');
            $table->index('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
