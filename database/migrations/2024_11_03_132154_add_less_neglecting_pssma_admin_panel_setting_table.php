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
            $table->integer('less_than_miniute_neglecting_passma')->default(3)->comment('اقل من كم دقيقة يتم اهمال البصمة التأكيدية للموظف خلال نفس الشفت')->after('sanctions_value_forth_abcence');
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
