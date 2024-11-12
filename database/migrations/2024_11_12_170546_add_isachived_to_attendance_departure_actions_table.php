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
        Schema::table('attendance_departure_actions', function (Blueprint $table) {
            $table->tinyInteger('is_archived')->comment('هل تمت الارشفة')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_departure_actions', function (Blueprint $table) {
            //
        });
    }
};
