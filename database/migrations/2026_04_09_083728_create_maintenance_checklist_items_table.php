<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->nullable()->constrained('maintenance_schedules')->cascadeOnDelete();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_required')->default(true);
            $table->boolean('requires_photo')->default(false);
            $table->timestamps();

            $table->index(['schedule_id', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_checklist_items');
    }
};
