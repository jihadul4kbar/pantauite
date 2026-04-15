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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('system name: super_admin, it_manager, it_staff, end_user');
            $table->string('display_name', 100)->comment('display name: Super Admin, IT Manager');
            $table->text('description')->nullable();
            $table->json('permissions')->comment('JSON array of permissions');
            $table->boolean('is_system_role')->default(false)->comment('cannot be deleted');
            $table->timestamps();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
