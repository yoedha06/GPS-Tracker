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
        Schema::create('history', function (Blueprint $table) {
            $table->id('id_history');
            $table->foreignId('device_id')->references('id_device')->on('device');
            $table->string('latlng');
            $table->string('bounds');
            $table->float('accuracy');
            $table->float('altitude');
            $table->float('altitude_acuracy');
            $table->float('heading');
            $table->float('speeds');
            $table->timestamp('date_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
