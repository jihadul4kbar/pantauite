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
        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->enum('workflow_stage', ['diterima', 'respon', 'foto_sebelum', 'dikerjakan', 'laporan', 'selesai'])
                ->default('diterima')
                ->after('is_solution')
                ->comment('workflow stage when comment was posted');
            
            $table->index('workflow_stage');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->dropIndex(['workflow_stage']);
            $table->dropColumn('workflow_stage');
        });
    }
};
