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
            $table->decimal('latitude', 10, 7)->nullable(); // Menyimpan nilai latitude dengan presisi 10 digit dan 7 digit di belakang koma
            $table->decimal('longitude', 10, 7)->nullable(); // Menyimpan nilai longitude dengan presisi 10 digit dan 7 digit di belakang koma
            $table->string('bounds')->nullable();
            $table->float('accuracy')->nullable();
            $table->float('altitude')->nullable();
            $table->float('altitude_acuracy')->nullable();
            $table->float('heading')->nullable();
            $table->float('speeds')->nullable();
            $table->timestamp('date_time')->nullable();
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
