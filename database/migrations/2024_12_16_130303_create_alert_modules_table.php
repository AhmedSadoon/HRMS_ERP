<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alert_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name',150);
            $table->tinyInteger('active')->default(1);
        });

        DB::table('alert_modules')->insert(
            [ 
             ['name'=>'الضبط العام','active'=>1],
             ['name'=>'شؤون الموظفين','active'=>1],
             ['name'=>'جهاز البصمة','active'=>1],
             ['name'=>'الاجر','active'=>1],
             ['name'=>'السنوي','active'=>1],
             ['name'=>'التحقيقات','active'=>1],
             ['name'=>'المراقبة','active'=>1],
             ['name'=>'الصلاحيات','active'=>1],
             

            ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_modules');
    }
};
