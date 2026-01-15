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
        Schema::create('ai_feature_store', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_log_id')->unique()->constrained('auth_logs')->cascadeOnDelete();
            $table->float('velocity_check')->nullable();
            $table->float('ip_reputation_score')->nullable();
            $table->float('device_trust_score')->nullable();
            $table->float('behavior_anomaly_score')->nullable();
            $table->string('label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_feature_store');
    }
};
