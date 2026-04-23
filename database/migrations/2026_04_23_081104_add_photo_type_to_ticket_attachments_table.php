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
        Schema::table('ticket_attachments', function (Blueprint $table) {
            $table->enum('photo_type', ['before', 'after', 'general'])->default('general')
                ->after('comment_id')
                ->comment('Type of photo: before (foto sebelum), after (foto sesudah), general (lampiran umum)');
            
            $table->index('photo_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_attachments', function (Blueprint $table) {
            $table->dropIndex(['photo_type']);
            $table->dropColumn('photo_type');
        });
    }
};
