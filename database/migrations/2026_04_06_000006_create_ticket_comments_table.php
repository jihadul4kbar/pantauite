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
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->comment('comment author');
            $table->text('comment');
            $table->boolean('is_internal')->default(false)->comment('internal note (not visible to end user)');
            $table->boolean('is_solution')->default(false)->comment('marked as solution');
            
            // Attachments
            $table->json('attachments')->nullable()->comment('array of file info: [{filename, path, size}]');
            
            // Audit
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('ticket_id');
            $table->index('user_id');
            $table->index('is_internal');
            $table->index('is_solution');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
    }
};
