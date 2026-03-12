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
            'telemetry',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('device_id')
                    ->constrained('devices')
                    ->onDelete('cascade')
                    ->nullable();
                $table->string('current')->nullable();
                $table->float('voltage', 4)->nullable();
                $table->string('power')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry');
    }
};
