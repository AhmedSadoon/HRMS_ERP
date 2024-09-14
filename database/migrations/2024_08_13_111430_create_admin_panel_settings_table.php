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
        Schema::create('admin_panel_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->tinyInteger('system_status')->default('1')->comment('one is active ,zero not active');
            $table->string('image',225)->nullable();
            $table->string('phone',225);
            $table->string('address');
            $table->string('email',100);
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->integer('com_code');
            $table->decimal('after_miniute_calculate_delay',10,2)->default(0)->comment('بعد كم دقيقة يحسب تأخير حضور');
            $table->decimal('after_miniute_calculate_early_departure',10,2)->default(0)->comment('بعد كم دقيقة يحسب انصراف مبكر');
            $table->decimal('after_miniute_quarterday',10,2)->default(0)->comment('بعد كم دقيقة مجموع الانصراف المبكر والحضور المتأخر يخصم ربع يوم');
            $table->decimal('after_time_half_dayCut',10,2)->default(0)->comment('بعد كم مرة تأخير او انصراف مبكر نخصم نص يوم');
            $table->decimal('after_time_allday_daycut',10,2)->default(0)->comment('نخصم بعد كم مرة تأخير او انصراف مبكر يوم كامل');
            $table->decimal('monthly_vaction_balance',10,2)->default(0)->comment('رصيد اجازات الموظف الشهري');
            $table->decimal('after_days_begins_vacation',10,2)->default(0)->comment('بعد كم يوم ينزل رصيد الاجازات');
            $table->decimal('first_balance_begin_vacation',10,2)->default(0)->comment('الرصيد الاولي المرحلة عند تفعيل الاجازات للموظف مثل نزول 10 ايام بعد ستة شهور للموظف');
            $table->decimal('sanctions_value_first_abcence',10,2)->default(0)->comment('قيمة خصم الايام بعد اول مرة غياب بدون عذر');
            $table->decimal('sanctions_value_second_abcence',10,2)->default(0)->comment('قيمة خصم الايام بعد ثاني مرة غياب بدون عذر');
            $table->decimal('sanctions_value_thaird_abcence',10,2)->default(0)->comment('قيمة خصم الايام بعد ثالث مرة غياب بدون عذر');
            $table->decimal('sanctions_value_forth_abcence',10,2)->default(0)->comment('قيمة خصم الايام بعد رابع مرة غياب بدون عذر');
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_panel_settings');
    }
};
