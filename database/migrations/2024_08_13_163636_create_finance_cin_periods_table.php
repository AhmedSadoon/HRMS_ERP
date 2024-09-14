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
        Schema::create('finance_cin_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_calenders_id');
            $table->foreign('finance_calenders_id')->references('id')->on('finance_calenders')->OnDelete('cascade')->onUpdate('cascade');
            $table->integer('number_of_dats');//عدد الايام
            $table->string('year_and_month',10);//عدد السنين والشهور
            $table->integer('finance_yr');//كود السنة المالية
            $table->integer('month_id');
            $table->date('start_date_m');// بداية الشهر المالية
            $table->date('end_date_m');//نهاية الشهر المالي
            $table->tinyInteger('is_open')->comment('صفر في انتظار الفتح -واحد مفتوح - اثنين مؤرشف')->default(0); //الشهر المالي مفتوح او مغلق
            $table->date('start_date_for_pasma');// بداية الشهر المالية للبصمة
            $table->date('end_date_for_pasma');//نهاية الشهر المالي للبصمة
            $table->integer('com_code');
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_cin_periods');
    }
};
