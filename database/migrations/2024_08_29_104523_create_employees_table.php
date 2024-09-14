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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('employees_code')->comment('كود الموظف التلقائي لا يتغير');
            $table->integer('zketo_code')->nullable()->comment('كود بصمة الموظف من جهاز البصمة لا يتغير');
            $table->string('emp_name', 300);
            $table->tinyInteger('emp_gender')->comment('نوع الجنس 1 ذكر 2 انثى');
            $table->integer('branch_id')->default(1)->comment('الفرع التابع له الموظف');
            $table->foreignId('qualifications_id')->nullable()->comment('المؤهل')->references('id')->on('qualifications')->onUpdate('cascade');
            $table->string('qualifications_year', 10)->nullable()->comment('سنة التخرج');
            $table->tinyInteger('graduation_estimate')->nullable()->comment('تقدير التخرج');
            $table->string('graduation_specialization', 225)->nullable()->comment('تخصص التخرج');
            $table->date('brith_date')->nullable()->comment('تاريخ ميلاد الموظف');
            $table->string('emp_national_identity', 50)->nullable()->comment('رقم البطاقة الشخصية الموظف');
            $table->date('emp_endDate_identityID')->nullable()->comment('تاريخ انتهاء صلاحية البطاقة الشخصية');
            $table->string('emp_idenity_place', 225)->nullable()->comment('مكان اصدار البطاقة الشخصية الموظف');
            $table->integer('blood_group_id')->nullable()->comment('فصيلة الدم');
            $table->foreignId('religion_id')->nullable()->comment('الديانة')->references('id')->on('religions')->onUpdate('cascade');
            $table->integer('emp_lang_id')->nullable()->comment('اللغة الاساسية للموظف');
            $table->string('emp_email', 100)->nullable()->comment('البريد الالكتروني للموظف');
            $table->integer('country_id')->nullable()->comment('دولة الموظف');
            $table->integer('governorate_id')->nullable()->comment('محافظة الموظف');
            $table->integer('city_id')->nullable()->comment('مدينة الموظف');
            $table->string('emp_home_tel', 50)->nullable()->comment('رقم هاتف المنزل');
            $table->string('emp_work_tel', 50)->nullable()->comment('رقم هاتف العمل');
            $table->integer('emp_military_status_id')->nullable()->comment('الحالة العسكرية');
            $table->date('emp_military_date_from')->nullable()->comment('تاريخ بداية الخدمة العسكرية');
            $table->date('emp_military_date_to')->nullable()->comment('تاريخ نهاية الخدمة العسكرية');
            $table->string('emp_military_wepon')->nullable()->comment('نوع سلاح الخدمة العسكرية');
            $table->date('exemption_date')->nullable()->comment('تاريخ الاعفاء من الخدمة العسكرية');
            $table->string('exemption_reason', 300)->nullable()->comment('سبب الاعفاء من الخدمة العسكرية');
            $table->string('postponement_reason',225)->nullable()->comment('سبب تأجيل الخدمة العسكرية');
            $table->date('date_resignation')->nullable()->comment('تاريخ ترك العمل');
            $table->string('resignation_reason', 300)->nullable()->comment('سبب ترك العمل');
            $table->tinyInteger('does_has_driving_license')->nullable()->default(0)->comment('هل يمتلك رخصة قيادة');
            $table->string('driving_license_degree', 50)->nullable()->comment('رقم رخصة القيادة');
            $table->integer('driving_license_types_id')->nullable();
            $table->tinyInteger('has_relatives')->nullable()->default(0)->comment('هل لهو اقارب بالعمل');
            $table->string('relatives_details', 600)->nullable()->comment('تفاصيل الاقارب في العمل');
            $table->text('notes')->nullable();
            $table->date('emp_start_date')->nullable()->comment('تاريخ بدأ العمل');
            $table->tinyInteger('function_status')->nullable()->default(0)->comment('حالة الموظف 1 يعمل 0 خارج الخدمة');
            $table->foreignId('emp_department_id')->nullable()->comment('القسم')->references('id')->on('departements')->onUpdate('cascade');
            $table->foreignId('emp_jobs_id')->nullable()->comment('نوع الوظيفة')->references('id')->on('jobs_categories')->onUpdate('cascade');
            $table->tinyInteger('does_has_ateendance')->default(1)->comment('هل ملزم الموظف بعمل بصمة حضور وانصراف');
            $table->tinyInteger('is_has_fixced_shift')->nullable()->comment('هل للموظف شفت ثابت');
            $table->foreignId('shift_type_id')->nullable()->references('id')->on('shifts_types')->onUpdate('cascade');
            $table->decimal('daily_work_hour', 10, 2)->nullable()->comment('عدد ساعات العمل للموظف في حالة ليس له شفت ثابت ');
            $table->decimal('emp_salary', 10, 2)->nullable()->default(0)->comment('راتب للموظف');    
            $table->tinyInteger('motivation_type')->nullable()->default(0)->comment('حافز الموظف 1 ثابت 0 لايوجد 2 متغير');
            $table->decimal('motivation', 10, 2)->nullable()->default(0)->comment('قيمة الحافز الثابت ان وجد ');
            $table->tinyInteger('is_social_nsurance')->nullable()->default(0)->comment('هل للموظف تأمين اجتماعي');
            $table->decimal('social_nsurance_cutMonthely', 10, 2)->nullable()->comment('قيمة استقطاع التأمين اجتماعي الشهر للموظف');
            $table->string('social_nsurance_number',50)->nullable()->comment('رقم التأمين الاجتماعي');
            $table->tinyInteger('is_medical_nsurance')->nullable()->default(0)->comment('هل للموظف تأمين صحي');
            $table->decimal('medical_nsurance_cutMonthely', 10, 2)->nullable()->comment('قيمة استقطاع التأمين صحي الشهر للموظف');
            $table->string('medical_nsurance_number',50)->nullable()->comment('رقم التأمين الصحي');
            $table->tinyInteger('sal_cach_or_visa')->nullable()->default(1)->comment('نوع صرف الراتب 1 كاش 2 فيزا');
            $table->tinyInteger('is_active_for_vaccation')->nullable()->default(0)->comment('هل هذا الموظف ينزل له رصيد اجازات');
            $table->string('urgent_person_details', 600)->nullable()->comment('تفاصيل شخص يمكن الرجوع اليه للوصول للموظف');
            $table->string('states_address', 300)->nullable()->comment('عنوان اقامة الموظف');
            $table->integer('childern_number')->nullable()->default(0);
            $table->integer('emp_social_status_id')->nullable()->comment('الحالة الاجتماعية');
            $table->foreignId('resignation_id')->nullable()->comment('نوع ترك العمل')->references('id')->on('resignations')->onUpdate('cascade');
            $table->string('bank_number_account', 50)->nullable()->comment('رقم حساب البنك للموظف');
            $table->tinyInteger('is_disabilities_processes')->nullable()->default(0)->comment('هل لهو اعاقة 0 لايوجد 1 يوجد');
            $table->string('disabilities_processes', 500)->nullable()->comment('نوع الاعاقة');
            $table->foreignId('emp_nationalitie_id')->nullable()->comment('الجنسية')->references('id')->on('nationalities')->onUpdate('cascade');
            $table->string('emp_cafel')->nullable()->comment('اسم الكفيل');
            $table->string('emp_pasport_no', 100)->nullable()->comment('رقم الجواز');
            $table->string('emp_pasport_from', 100)->nullable()->comment('مكان اصدار الجواز الموظف');
            $table->date('emp_pasport_exp')->nullable()->comment('تاريخ انتهاء صلاحية الجواز الشخصية');
            $table->string('emp_photo', 100)->nullable()->comment('صورة للموظف');
            $table->string('emp_cv',100)->comment('السيرة الذاتية للموظف')->nullable();
            $table->string('emp_Basic_stay_com',300)->nullable()->comment('عنوان اقامة الموظف في بلده الام');
            $table->date('date')->nullable();
            $table->decimal('day_price', 10, 2)->nullable()->comment('سعر يوم الموظف');
            $table->tinyInteger('does_have_fixed_allowances')->nullable()->default(0)->comment('هل له بدل ثابت');
            $table->tinyInteger('is_done_vaccation_formula')->nullable()->default(0)->comment('هل تمت المعادلة التلقائية لاحتساب الرصيد السنوي للموظف');
            $table->tinyInteger('is_sensitive_manager_data')->nullable()->default(0)->comment('هل بيانات حساسة للمديرين مثلا ولا تظهر الا بصلاحيات خاصة');
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
        Schema::dropIfExists('employees');
    }
};
