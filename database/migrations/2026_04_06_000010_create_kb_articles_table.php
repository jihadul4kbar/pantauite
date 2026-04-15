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
        Schema::create('kb_articles', function (Blueprint $table) {
            $table->id();
            $table->string('article_number', 50)->unique()->comment('format: KB-NNNN');
            $table->foreignId('category_id')->constrained('kb_categories')->restrictOnDelete();
            
            $table->string('title');
            $table->string('slug');
            $table->longText('content');
            $table->text('summary')->nullable()->comment('short description/excerpt');
            
            // Organization
            $table->json('tags')->nullable()->comment('array of tags');
            $table->boolean('is_featured')->default(false)->comment('pinned/highlighted');
            $table->boolean('is_internal')->default(false)->comment('IT staff only');
            
            // Versioning (simplified)
            $table->unsignedInteger('version')->default(1);
            $table->text('changelog')->nullable()->comment('what changed in latest version');
            
            // Status
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            
            // Authorship
            $table->foreignId('author_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete()->comment('reviewer/approver');
            $table->timestamp('reviewed_at')->nullable();
            
            // Analytics
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('helpful_votes')->default(0);
            $table->unsignedInteger('not_helpful_votes')->default(0);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('article_number');
            $table->index('category_id');
            $table->index('slug');
            $table->index('status');
            $table->index('author_id');
            $table->index('is_featured');
            $table->index('is_internal');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kb_articles');
    }
};
