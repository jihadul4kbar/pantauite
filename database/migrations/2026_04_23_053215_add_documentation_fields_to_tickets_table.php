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
            $table->boolean('before_photos_uploaded')->default(false)
                ->after('closed_at')
                ->comment('documentation milestone: before photos uploaded');
            
            $table->boolean('after_photos_uploaded')->default(false)
                ->after('before_photos_uploaded')
                ->comment('documentation milestone: after photos uploaded');
            
            $table->boolean('completion_report_submitted')->default(false)
                ->after('after_photos_uploaded')
                ->comment('documentation milestone: completion report submitted');
            
            $table->index('before_photos_uploaded');
            $table->index('after_photos_uploaded');
            $table->index('completion_report_submitted');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['before_photos_uploaded']);
            $table->dropIndex(['after_photos_uploaded']);
            $table->dropIndex(['completion_report_submitted']);
            $table->dropColumn(['before_photos_uploaded', 'after_photos_uploaded', 'completion_report_submitted']);
        });
    }
};
