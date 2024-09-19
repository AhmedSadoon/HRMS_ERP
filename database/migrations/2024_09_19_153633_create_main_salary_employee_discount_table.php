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
        Schema::create('main_salary_employee_discount', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_salary_employee_id')->references('id')->on('main_salary_employee')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('finance_cin_periods_id')->comment('كود الشهر المالي')->references('id')->on('finance_cin_periods')->onUpdate('cascade');
            $table->integer('is_auto')->comment('هل تلقائي من النظام ام بشكل يدوي')->default(0);
            $table->bigInteger('employees_code');
            $table->decimal('day_price',10,2)->comment('اجر يوم الموظف');
            $table->foreignId('discount_types_id')->comment('نوع الخصم')->references('id')->on('discount_types')->onUpdate('cascade');
            $table->decimal('total',10,2)->comment('اجمالي الخصم');
            $table->integer('is_archived')->comment('حالة الارشفة')->default(0);
            $table->foreignId('archived_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->dateTime('archived_at')->nullable();
            $table->string('notes',100)->nullable();
            $table->integer('active')->default(1);
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
        Schema::dropIfExists('main_salary_employee_discount');
    }
};
