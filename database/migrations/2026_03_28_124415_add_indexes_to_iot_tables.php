<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('telemetries', function (Blueprint $table) {
            $table->index(['device_id', 'measured_at']);
        });

        Schema::table('device_commands', function (Blueprint $table) {
            $table->index(['device_id', 'executed']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->index('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iot_tables', function (Blueprint $table) {
            //
        });
    }
};
