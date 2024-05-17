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
        Schema::table('type_notification', function (Blueprint $table) {
            $table->string('phone_number')->after('user_id');
            $table->tinyInteger('remaining_count')->after('count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('type_notification', function (Blueprint $table) {
            //
        });
    }
};
