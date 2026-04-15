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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique()->comment('format: TKT-YYYY-NNNN');
            $table->string('subject');
            $table->text('description');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'reopened'])->default('open');
            $table->enum('priority', ['critical', 'high', 'medium', 'low'])->default('medium');
            
            // Relationships
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->comment('ticket creator');
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete()->comment('assigned IT staff');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete()->comment('responsible department');
            $table->foreignId('category_id')->nullable()->constrained('ticket_categories')->nullOnDelete();
            
            // SLA
            $table->foreignId('sla_policy_id')->nullable()->constrained('sla_policies')->nullOnDelete();
            $table->timestamp('sla_deadline')->nullable()->comment('when SLA breaches');
            $table->boolean('sla_breached')->default(false);
            $table->timestamp('sla_breached_at')->nullable();
            $table->timestamp('paused_at')->nullable()->comment('SLA paused when waiting for user');
            
            // Timestamps
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('first_response_at')->nullable()->comment('first response time tracking');
            
            // Additional
            $table->enum('source', ['web', 'email', 'phone', 'walk-in'])->default('web');
            $table->text('resolution_notes')->nullable();
            $table->tinyInteger('satisfaction_rating')->nullable()->comment('1-5 stars');
            $table->text('satisfaction_feedback')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('ticket_number');
            $table->index('status');
            $table->index('priority');
            $table->index('user_id');
            $table->index('assignee_id');
            $table->index('department_id');
            $table->index('category_id');
            $table->index('created_at');
            $table->index('sla_deadline');
            $table->index(['status', 'priority']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
