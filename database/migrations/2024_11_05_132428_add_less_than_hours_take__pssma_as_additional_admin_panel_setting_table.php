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
        Schema::table('admin_panel_settings', function (Blueprint $table) {
            //
            $table->decimal('max_hours_take_Pssma_as_additional',10,2)->default(3)->comment('الحد الاقصى لاحتساب عدد ساعات العمل الاضافية عند انصراف الموظف واحتساب بصمة الانصراف والا ستحتسب على انها بصمة حضور شفت جديد')->after('less_than_miniute_neglecting_passma');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_panel_settings', function (Blueprint $table) {
            //
        });
    }
};
