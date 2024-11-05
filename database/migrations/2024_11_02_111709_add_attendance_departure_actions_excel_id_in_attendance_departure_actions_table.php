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
            $table->bigInteger('AttendanceDepartureActionsExcelId')->comment('رقم البصمة في الارشيف');

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
