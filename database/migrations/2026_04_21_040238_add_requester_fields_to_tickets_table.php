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
            $table->string('requester_name')->nullable()->after('department_id');
            $table->string('requester_email')->nullable()->after('requester_name');
            $table->string('requester_department')->nullable()->after('requester_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['requester_name', 'requester_email', 'requester_department']);
        });
    }
};
