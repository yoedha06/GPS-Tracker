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
        Schema::create('location2', function (Blueprint $table) {
            $table->id();
            $table->text('lat')->nullable();
            $table->text('lon')->nullable();
            $table->text('bon')->nullable();
            $table->json('original')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location2');
    }
};
