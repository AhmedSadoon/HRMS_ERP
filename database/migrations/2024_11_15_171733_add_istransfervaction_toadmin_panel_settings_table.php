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
            $table->tinyInteger('is_transfer_vacction')->default('0')->comment('ترحيل الرصيد من السنة الى السنة القادمة');

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
