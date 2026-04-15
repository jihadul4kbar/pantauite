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
        Schema::create('report_runs', function (Blueprint $table) {
            $table->id();
            $table->string('report_type', 50);
            $table->json('filters')->nullable()->comment('filters used untuk this run');
            $table->enum('format', ['pdf', 'excel', 'csv']);
            $table->string('file_path', 500)->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->foreignId('generated_by')->constrained('users')->restrictOnDelete();
            $table->unsignedInteger('generation_time_ms')->nullable()->comment('how long it took');
            $table->timestamp('created_at')->useCurrent();

            $table->index('generated_by');
            $table->index('format');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_runs');
    }
};
