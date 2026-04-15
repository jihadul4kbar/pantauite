<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('maintenance_tasks')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_filename');
            $table->integer('file_size')->nullable();
            $table->string('photo_type'); // before/after/during/evidence
            $table->string('caption')->nullable();
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['task_id', 'photo_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_photos');
    }
};
