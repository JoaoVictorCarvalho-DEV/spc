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
        Schema::create(
            'devices',
            function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();
                $table->string('device_uid')->nullable();

                $table->string('esp_identifier')->unique();
                $table->string('api_token', 80)->unique();

                $table->string('location')->nullable();
                $table->string('status')->default('offline');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
