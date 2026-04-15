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
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('related_kb_article_id')
                ->nullable()
                ->after('category_id')
                ->constrained('kb_articles')
                ->nullOnDelete();
            
            $table->index('related_kb_article_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['related_kb_article_id']);
            $table->dropIndex(['related_kb_article_id']);
            $table->dropColumn('related_kb_article_id');
        });
    }
};
