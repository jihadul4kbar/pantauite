<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("sla_policies", function (Blueprint $table) {
            $table->id();
            $table->string("name", 100)->comment("policy name");
            $table
                ->enum("priority", ["critical", "high", "medium", "low"])
                ->unique();

            // Time in minutes
            $table
                ->unsignedInteger("response_time_minutes")
                ->comment("max time for first response");
            $table
                ->unsignedInteger("resolution_time_minutes")
                ->comment("max time to resolve");

            // Business hours
            $table
                ->boolean("use_business_hours")
                ->default(true)
                ->comment("if false, 24/7 SLA");
            $table->time("business_hours_start")->default("08:00:00");
            $table->time("business_hours_end")->default("17:00:00");
            $table->json("business_days")->comment("1=Mon, 7=Sun");

            // Escalation
            $table->boolean("escalation_enabled")->default(false);
            $table
                ->unsignedInteger("escalation_threshold_minutes")
                ->nullable()
                ->comment("warn before breach");
            $table
                ->foreignId("escalation_user_id")
                ->nullable()
                ->constrained("users")
                ->nullOnDelete()
                ->comment("escalate to this user");

            $table->boolean("is_active")->default(true);
            $table->text("description")->nullable();
            $table->timestamps();

            $table->index("priority");
            $table->index("is_active");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("sla_policies");
    }
};
