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
        Schema::create('attendance_departure', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_cin_periods_id')->comment('كود الشهر المالي');
            $table->bigInteger('employees_code')->comment('كود الموظف الثابت');
            $table->decimal('shift_hour_contract',10,2)->comment('عدد ساعات العمل اليومي المتعاقد عليها في ذلك الوقت')->nullable();
            $table->tinyInteger('status_move')->comment('1-checkin 2-checkout')->nullable();
            $table->date('datein')->comment('تاريخ  الدخول')->nullable();
            $table->date('dateOut')->comment('تاريخ  الانصراف')->nullable();
            $table->time('time_in')->comment(' ووقت الدخول')->nullable();
            $table->time('time_out')->comment(' ووقت الانصراف')->nullable();
            $table->dateTime('datetime_in')->comment('توقيت بصمة الحضور')->nullable();
            $table->dateTime('datetime_out')->comment('توقيت بصمة الانصراف')->nullable();
            $table->string('variables',250)->comment('المتغيرات')->nullable();
            $table->tinyInteger('attedance_dely')->comment('هل الحضور متأخر')->default(0);
            $table->tinyInteger('early_departure')->comment('هل انصراف مبكر')->default(0);
            $table->string('azn_hours',250)->comment('تفاصيل الاذن ان وجد')->nullable();
            $table->decimal('total_hours',10,2)->comment('عدد ساعات العمل بين توقيت الحضور والانصراف')->default(0);
            $table->decimal('absen_hours',10,2)->comment('عدد ساعات الغياب بهذا اليوم')->default(0);
            $table->decimal('additional_hours',10,2)->comment('عدد ساعات الاضافي بهذا اليوم')->default(0);
            $table->tinyInteger('is_made_action_on_emp')->comment('هل تم اخذ اجراء على الموظف')->default(0);
            $table->tinyInteger('is_archived')->comment('هل تمت الارشفة')->default(0);
            $table->dateTime('archived_date')->comment('تاريخ الارشفة')->nullable();
            $table->foreignId('archived_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('vacations_type_id')->comment('نوع الاجازة')->nullable();
            $table->integer('occasions_id')->comment('اجازات رسمية في حالة نوع الاجازة رسمية')->nullable();
            $table->tinyInteger('cut')->comment('0-nothing .25- qurater day .5-half day 1-one day')->default(0);
            $table->string('year_and_month',10)->comment('السنة والشهر المالي')->nullable()->default('0');
            $table->integer('branch_id')->comment('كود الفرع لحضة فتح الشهر المالي')->nullable();
            $table->integer('function_status')->comment('حالة الموظف لحضة فتح الشهر المالي');

            $table->dateTime('datetimeAction')->comment('توقيت البصمة من جهاز البصمة');
            $table->foreignId('main_salary_employee_id')->comment("كود الراتب بالشهر المالي ان وجد")->nullable();
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
        Schema::dropIfExists('attendance_departure');
    }
};
