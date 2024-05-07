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
        Schema::create('team', function (Blueprint $table) {
            $table->id();
            $table->text('informasi')->nullable();
            $table->string('username_1')->nullable();
            $table->string('username_2')->nullable();
            $table->string('username_3')->nullable();
            $table->string('username_4')->nullable();
            $table->string('posisi_1')->nullable();
            $table->string('posisi_2')->nullable();
            $table->string('posisi_3')->nullable();
            $table->string('posisi_4')->nullable();
            $table->text('deskripsi_1')->nullable();
            $table->text('deskripsi_2')->nullable();
            $table->text('deskripsi_3')->nullable();
            $table->text('deskripsi_4')->nullable();
            $table->string('photo_1')->nullable();
            $table->string('photo_2')->nullable();
            $table->string('photo_3')->nullable();
            $table->string('photo_4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team');
    }
};
