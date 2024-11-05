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
        Schema::create('attendance_departure_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_departure_id')->references('id')->on('attendance_departure')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('finance_cin_periods_id')->comment('كود الشهر المالي');
            $table->bigInteger('employees_code')->comment('كود الموظف الثابت');
            $table->dateTime('datetimeAction')->comment('وقت البصمة');
            $table->tinyInteger('action_type')->comment('نوع حركة البصمة');
            $table->tinyInteger('it_is_active_with_parent')->comment('هل هي المستعملة بتفعيل الاب')->default(0);
            $table->tinyInteger('added_method')->comment('1- dynamic pasma 2-manual')->default(1);
           $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('com_code');      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_departure_actions');
    }
};
