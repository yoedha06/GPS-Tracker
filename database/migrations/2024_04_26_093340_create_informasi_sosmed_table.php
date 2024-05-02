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
        Schema::create('informasi_sosmed', function (Blueprint $table) {
            $table->id();
            $table->string('title_sosmed')->nullable();
            $table->string('street_name')->nullable();
            $table->string('subdistrict')->nullable();
            $table->string('ward')->nullable();
            $table->string('call')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi_sosmed');
    }
};
