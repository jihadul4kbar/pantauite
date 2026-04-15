<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_checklist_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('maintenance_tasks')->cascadeOnDelete();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('status'); // pass/fail/na
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->datetime('checked_at')->nullable();
            $table->foreignId('checked_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['task_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_checklist_results');
    }
};
