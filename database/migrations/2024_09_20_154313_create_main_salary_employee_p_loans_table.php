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
        Schema::create('main_salary_employee_p_loans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employees_code');
            $table->decimal('emp_salary', 10,2)->comment('راتب الموظف');
            $table->decimal('total',10,2)->comment('اجمالي السلفة ');
            $table->integer('month_number')->comment('عدد الشهور')->default(0);
            $table->decimal('month_kast_value', 10,2)->comment('قيمة القسط الشهري');
            $table->string('year_and_month_start',10)->comment('بيدا السداد من الشهر المالي ')->default(0);
            $table->decimal('what_paid',10,2)->comment('اجمالي المدفوع ')->default(0);
            $table->decimal('what_remain',10,2)->comment('اجمالي المتبقي ')->default(0);
            $table->integer('is_dismissail_done')->comment('حالة الصرف')->default(0);
            $table->foreignId('dismissail_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->dateTime('dismissail_at')->nullable();

            $table->integer('is_archived')->comment('حالة الارشفة')->default(0);
            $table->foreignId('archived_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->dateTime('archived_at')->nullable();
            $table->string('notes',100)->nullable();
            $table->integer('com_code');
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_salary_employee_p_loans');
    }
};
