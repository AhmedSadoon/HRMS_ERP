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
            $table->decimal('sanctions_days_counter',10,2)->comment('عدد جزاءات الايام')->default(0);
            $table->decimal('sanctions_days_total',10,2)->comment('اجمالي جزاءات الايام')->default(0);
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
