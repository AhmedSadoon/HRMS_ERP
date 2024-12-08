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
        Schema::create('main_employee_investigations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_cin_periods_id')->comment('كود الشهر المالي')->references('id')->on('finance_cin_periods')->onUpdate('cascade');
            $table->integer('is_auto')->comment('هل تلقائي من النظام ام بشكل يدوي واحد تلقائي صفر يدوي')->default(0);
            $table->bigInteger('employees_code');
            $table->text('content')->comment('محتوى التحقيق');
            $table->integer('is_archived')->comment('حالة الارشفة تعتبر هي الاعتماد')->default(0);
            $table->foreignId('archived_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->dateTime('archived_at')->nullable();
            $table->string('notes',300)->nullable();
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
        Schema::dropIfExists('main_employee_sanction_investigations');
    }
};
