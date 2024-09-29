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
            $table->integer('is_take_action_diss_collec')->comment('هل تم اخذ اجراء لصرف او تحصيل الراتب خلال الشهر')->nullable()->default('0');
            $table->decimal('final_the_net_after_close_for_trahil',10,2)->comment('صافي قيمة الراتب بعد اخذ اجراء ويعتبر الرصيد المرحل للشهر الجديد فقط')->nullable()->default('0');

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
