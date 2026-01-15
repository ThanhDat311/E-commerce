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
        Schema::create('auth_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('session_id')->nullable();
            $table->string('ip_address');
            $table->string('device_fingerprint')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('geo_location')->nullable();
            $table->float('risk_score')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->nullable();
            $table->enum('auth_decision', [
                'passive_auth_allow',
                'challenge_otp',
                'challenge_biometric',
                'block_access'
            ])->nullable();
            $table->boolean('is_successful')->default(false);
            $table->timestamp('login_timestamp')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_logs');
    }
};
