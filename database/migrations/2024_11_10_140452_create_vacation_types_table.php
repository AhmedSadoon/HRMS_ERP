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
        Schema::create('vacation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name',150);
        });
        DB::table('vacation_types')->insert(
            [ 
             ['name'=>'لا'],
             ['name'=>'راحة اسبوعية'],
             ['name'=>'سنوي'],
             ['name'=>'بدل راحة'],
             ['name'=>'اجازة رسمية'],
             ['name'=>'غياب بدون اذن'],
             ['name'=>'غياب بدون اجر'],
             ['name'=>'وضع'],
             ['name'=>'ميلاد'],
             ['name'=>'وفاة'],
             ['name'=>'مرضية'],
             ['name'=>'زواج'],
             ['name'=>'اخرى'],
                      
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacation_types');
    }
};
