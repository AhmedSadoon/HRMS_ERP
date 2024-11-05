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
        Schema::create('weekdays', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('name_en',50);
        });

        DB::table('weekdays')->insert(
           [ 
            ['name'=>'السبت','name_en'=>'Saturday'],
           ['name'=>'الاحد','name_en'=>'Sunday'],
           ['name'=>'الاثنين','name_en'=>'Monday'],
           ['name'=>'الثلاثاء','name_en'=>'Tuesday'],
           ['name'=>'الاربعاء','name_en'=>'Wednesday'],
           ['name'=>'الخميس','name_en'=>'Thursday'],
           ['name'=>'الجمعة','name_en'=>'Friday'],
      
           ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekdays');
    }
};
