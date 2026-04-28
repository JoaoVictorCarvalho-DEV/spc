<?php

use Carbon\Carbon;
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
        Schema::create('device_applience_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('applience_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->dateTime('started_at')->default(Carbon::now());
            $table->dateTime('ended_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_applience_history');
    }
};
