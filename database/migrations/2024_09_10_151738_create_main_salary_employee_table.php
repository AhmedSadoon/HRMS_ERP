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
        Schema::create('main_salary_employee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_cin_periods_id')->comment('كود الشهر المالي')->references('id')->on('finance_cin_periods')->onUpdate('cascade');
            $table->integer('employees_code')->comment('كود الموظف');
            $table->string('emp_name',300)->comment('اسم الموظف لحظة فتح الراتب');
            $table->decimal('day_price',10,2)->comment('قيمة يوم الموظف لهذا الراتب')->default('0');
            $table->integer('is_sensitive_manager_data')->comment('هل موظف ادارة عليا بيانات حساسة')->nullable()->default(0);
            $table->integer('branch_id')->comment('كود الفرع لحضة فتح الشهر المالي')->nullable();
            $table->integer('function_status')->comment('حالة الموظف لحضة فتح الشهر المالي');
            $table->integer('emp_department_id')->comment('ادارة الموظف لحضة فتح الشهر المالي')->nullable();
            $table->integer('emp_jobs_id')->comment('وظيفة الموظف لحضة فتح الشهر المالي')->nullable();
            $table->decimal('additions',10,2)->comment('اجمالي المكافئات المالية')->nullable()->default('0');
            $table->decimal('motivation',10,2)->comment('اجمالي الحافز مع العلم ممكن ان يكون ثابت او متفير ')->nullable()->default('0');
            $table->decimal('additional_days_counter',10,2)->comment('اجمالي ايام الاضافي للراتب ')->nullable()->default('0');
            $table->decimal('additional_days',10,2)->comment('اجمالي قيمة ايام الاضافي للراتب ')->nullable()->default('0');
            $table->decimal('absence_days_counter',10,2)->comment('عدد ايام الغياب  للراتب ')->nullable()->default('0');
            $table->decimal('absence_days',10,2)->comment('اجمالي قيمة ايام الغياب  للراتب ')->nullable()->default('0');
            $table->decimal('monthly_loan',10,2)->comment('اجمالي قيمة المستقطع سلف شهرية للراتب ')->nullable()->default('0');
            $table->decimal('permanent_loan',10,2)->comment('اجمالي قيمة المستقطع سلف مستديمة للراتب ')->nullable()->default('0');
            $table->decimal('discount',10,2)->comment('اجمالي قيمة خصومات الراتب ')->nullable()->default('0');
            $table->decimal('phones',10,2)->comment('اجمالي قيمة خصومات الهاتف من الراتب ')->nullable()->default('0');
            $table->decimal('medical_nsurance_cutMonthely',10,2)->comment('اجمالي قيمة خصم التأمين الطبي من الراتب ')->nullable()->default('0');
            $table->decimal('social_nsurance_cutMonthely',10,2)->comment('اجمالي قيمة خصم التأمين الاجتماعي من الراتب ')->nullable()->default('0');
            $table->decimal('fixed_suits',10,2)->comment('قيمة البدلات الثابتة للراتب ')->nullable()->default('0');
            $table->decimal('changable_suits',10,2)->comment('قيمة البدلات المتغيرة للراتب ')->nullable()->default('0');
            $table->decimal('total_benefits',10,2)->comment('اجمالي الاستحقاق للموظف ')->nullable()->default('0');
            $table->decimal('total_deductions',10,2)->comment('اجمالي المستقطع للموظف ')->nullable()->default('0');
            $table->decimal('sanctions_days_counter_type1',10,2)->comment('عدد ايام الجزاء')->nullable()->default('0');
            $table->decimal('sanctions_days_total_type1',10,2)->comment('قيمة ايام الجزاء')->nullable()->default('0');
            $table->decimal('sanctions_days_counter_type2',10,2)->comment('عدد ايام جزاء البصمة')->nullable()->default('0');
            $table->decimal('sanctions_days_total_type2',10,2)->comment('قيمة ايام جزاء البصمة')->nullable()->default('0');
            $table->decimal('sanctions_days_counter_type3',10,2)->comment('عدد ايام جزاء الغياب')->nullable()->default('0');
            $table->decimal('sanctions_days_total_type3',10,2)->comment('قيمة ايام جزاء الغياب')->nullable()->default('0');
            $table->decimal('sanctions_days_total_type2_type1',10,2)->comment('اجمالي ايام جزاء')->nullable()->default('0');
            $table->decimal('emp_sal',10,2)->comment('قيمة راتب الموظف')->nullable()->default('0');
            $table->decimal('last_salary_remain_balance',10,2)->comment('قيمة الراتب المرحل من الشهر السابق')->nullable()->default('0');
            $table->decimal('last_salary_record_id',10,2)->comment('رقم الراتب من الشهر السابق')->nullable()->default('0');
            $table->decimal('final_the_net',10,2)->comment('صافي قيمة الراتب')->nullable()->default('0');
            $table->string('year_and_month',10)->comment('السنة والشهر المالي')->nullable()->default('0');
            $table->integer('finance_yr')->comment('السنة المالية')->nullable()->default('0');
            $table->integer('sal_cach_or_visa')->comment('نوع الراتب هذا كاش او فيزا')->nullable()->default('0');
            $table->integer('is_stoped')->comment('هل هذا الراتب موقوف')->nullable()->default('0');
            $table->foreignId('archived_by')->comment('من قام بأرشفة الراتب')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('is_archived')->comment('هل تم ارشفة الراتب')->nullable()->default('0');
            $table->dateTime('archived_date')->comment('تاريخ ارشفة الراتب')->nullable();   
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('com_code')->comment('كود الشركة التابع لها الموظف');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_salary_employee');
    }
};
