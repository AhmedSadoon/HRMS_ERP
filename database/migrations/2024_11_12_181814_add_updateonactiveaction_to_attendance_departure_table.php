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
        Schema::table('attendance_departure', function (Blueprint $table) {
            $table->tinyInteger('is_updated_active_action')->comment('هل تم التعديل على البصمات الفعلية ')->default(0);
            $table->dateTime('is_updated_active_action_date')->comment('تاريخ التعديل')->nullable();
            $table->foreignId('is_updated_active_action_by')->comment('اخر من قام بالتعديل على البصمات الفعلية')->nullable()->references('id')->on('admins')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_departure', function (Blueprint $table) {
            //
        });
    }
};
