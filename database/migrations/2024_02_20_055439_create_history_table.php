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
            $table->decimal('latitude', 10, 7); // Menyimpan nilai latitude dengan presisi 10 digit dan 7 digit di belakang koma
            $table->decimal('longitude', 10, 7); // Menyimpan nilai longitude dengan presisi 10 digit dan 7 digit di belakang koma
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
