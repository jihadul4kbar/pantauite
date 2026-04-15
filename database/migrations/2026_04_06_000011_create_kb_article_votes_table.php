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
        Schema::create('kb_article_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('kb_articles')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('vote_type', ['helpful', 'not_helpful']);
            $table->text('feedback')->nullable()->comment('optional feedback');
            $table->timestamps();

            // Prevent duplicate votes
            $table->unique(['article_id', 'user_id'], 'unique_article_user_vote');

            $table->index('article_id');
            $table->index('user_id');
            $table->index('vote_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kb_article_votes');
    }
};
