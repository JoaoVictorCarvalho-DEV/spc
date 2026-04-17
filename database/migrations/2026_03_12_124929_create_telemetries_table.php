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
            'telemetries',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('device_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->float('voltage')->nullable();
                $table->float('current');
                $table->float('power');
                $table->float('consumption')->nullable(); // kWh acumulado

                $table->timestamp('measured_at');

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
