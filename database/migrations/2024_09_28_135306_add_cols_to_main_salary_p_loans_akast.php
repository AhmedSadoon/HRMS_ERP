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
        Schema::table('main_salary_p_loans_akast', function (Blueprint $table) {
            $table->integer('is_parent_dismissail_done')->comment('حالة صرف الاب')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_salary_p_loans_akast', function (Blueprint $table) {
            //
        });
    }
};
