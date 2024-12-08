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
        Schema::create('main_employees_vacations_balance', function (Blueprint $table) {
            $table->id();
            $table->integer('employees_code')->comment('كود الموظف');
            $table->string('year_and_month',10)->comment('السنة والشهر المالي')->nullable()->default('0');
            $table->integer('finance_yr')->comment('السنة المالية')->nullable()->default('0');
            $table->decimal('carryover_from_previous_month',10,2)->comment('الرصيد المرحل من الشهر السابق ')->nullable()->default('0');
            $table->decimal('current_month_balance',10,2)->comment('الرصيد الشهر الحالي')->nullable()->default('0');
            $table->decimal('total_available_balance',10,2)->comment(' الرصيد الكلي المتوفر')->nullable()->default('0');
            $table->decimal('spent_balance',10,2)->comment(' الرصيد المستهلك')->nullable()->default('0');
            $table->decimal('net_balance',10,2)->comment(' صافي الرصيد')->nullable()->default('0');
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
        Schema::dropIfExists('main_employees_vacations_balance');
    }
};
