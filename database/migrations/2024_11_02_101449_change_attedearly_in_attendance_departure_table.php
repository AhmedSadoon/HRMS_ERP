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
            $table->decimal('attedance_dely',10,2)->comment('قيمة عدد دقائق الحضور المتأخر')->default(0)->change();
            $table->decimal('early_departure',10,2)->comment('قيمة عدد دقائق الانصراف مبكر')->default(0)->change();
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
