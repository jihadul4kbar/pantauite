<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('maintenance_tasks')->cascadeOnDelete();
            $table->foreignId('evaluated_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->date('evaluation_date');
            $table->tinyInteger('overall_rating')->nullable(); // 1-5
            $table->text('asset_condition_before')->nullable();
            $table->text('asset_condition_after')->nullable();
            $table->text('issues_found')->nullable();
            $table->text('recommendations')->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->text('follow_up_notes')->nullable();
            $table->text('next_maintenance_recommendation')->nullable();
            $table->decimal('asset_health_score', 5, 2)->nullable(); // 0-100
            $table->timestamps();

            $table->index(['task_id']);
            $table->index(['evaluation_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_evaluations');
    }
};
