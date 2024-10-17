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
        Schema::create('attendance_departure_actions_excel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_cin_periods_id')->comment('كود الشهر المالي');
            $table->bigInteger('employees_code')->comment('كود الموظف الثابت');
            $table->dateTime('datetimeAction')->comment('توقيت البصمة من جهاز البصمة');
            $table->tinyInteger('action_type')->comment('نوع حركة البصمة');
            $table->foreignId('main_salary_employee_id')->comment("كود الراتب بالشهر المالي ان وجد")->nullable();
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->dateTime('created_at')->comment('تاريخ الاضافة');
            $table->integer('com_code');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_departure_actrions_excel');
    }
};
