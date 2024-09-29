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
        Schema::table('main_salary_employee', function (Blueprint $table) {
            $table->dropColumn('sanctions_days_counter_type1');
            $table->dropColumn('sanctions_days_total_type1');
            $table->dropColumn('sanctions_days_counter_type2');
            $table->dropColumn('sanctions_days_total_type2');
            $table->dropColumn('sanctions_days_counter_type3');
            $table->dropColumn('sanctions_days_total_type3');
            $table->dropColumn('sanctions_days_total_type2_type1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_salary_employee', function (Blueprint $table) {
            //
        });
    }
};
