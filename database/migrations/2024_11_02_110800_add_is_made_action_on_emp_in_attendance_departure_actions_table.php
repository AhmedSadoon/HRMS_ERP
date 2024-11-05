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
            $table->tinyInteger('is_made_action_on_emp')->default(0)->comment('هل تم اخذ اجراء على الموظف');
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
