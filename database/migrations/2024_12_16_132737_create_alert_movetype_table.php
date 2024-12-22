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
        Schema::create('alert_movetype', function (Blueprint $table) {
            $table->id();
            $table->string('name',225);
            $table->foreignId('alert_modules_id')->nullable()->references('id')->on('alert_modules')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('active')->default(1);

        });

        DB::table('alert_movetype')->insert(
            [ 
             ['name'=>'تعديل الضبط العام','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة سنة مالية','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل سنة مالية','alert_modules_id'=>1,'active'=>1],
             ['name'=>'فتح سنة مالية','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اغلاق سنة مالية','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة فرع جديد','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل فرع','alert_modules_id'=>1,'active'=>1],
             ['name'=>'حذف فرع','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة شفت جديد','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل شفت','alert_modules_id'=>1,'active'=>1],
             ['name'=>'حذف شفت','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة ادارة جديدة','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل ادارة','alert_modules_id'=>1,'active'=>1],
             ['name'=>'حذف ادارة','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة وظيفة جديدة','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل وظيفة','alert_modules_id'=>1,'active'=>1],
             ['name'=>'حذف وظيفة','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة مؤهل جديد','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل مؤهل','alert_modules_id'=>1,'active'=>1],
             ['name'=>'حذف مؤهل','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة مناسبات جديدة','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل مناسبات','alert_modules_id'=>1,'active'=>1],
             ['name'=>'حذف مناسبات','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة نوع ترك العمل جديد','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل نوع ترك العمل','alert_modules_id'=>1,'active'=>1],
             ['name'=>'حذف نوع ترك العمل','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة جنسيات جديدة','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل جنسيات','alert_modules_id'=>1,'active'=>1],
             ['name'=>'حذف جنسيات','alert_modules_id'=>1,'active'=>1],
             ['name'=>'اضافة ديانات جديدة','alert_modules_id'=>1,'active'=>1],
             ['name'=>'تعديل ديانات','alert_modules_id'=>1,'active'=>1],
             ['name'=>'حذف ديانات','alert_modules_id'=>1,'active'=>1],

             ['name'=>'اضافة موظف جديد','alert_modules_id'=>2,'active'=>1],
             ['name'=>'تعديل موظف','alert_modules_id'=>2,'active'=>1],
             ['name'=>'حذف موظف','alert_modules_id'=>2,'active'=>1],
             ['name'=>'افارق ملفات الموظف','alert_modules_id'=>2,'active'=>1],
             ['name'=>'حذف ملفات الموظف','alert_modules_id'=>2,'active'=>1],
             ['name'=>'تحديث راتب الموظف','alert_modules_id'=>2,'active'=>1],
             ['name'=>'تحديث حافز الموظف','alert_modules_id'=>2,'active'=>1],
             ['name'=>'اضافة بدل الموظف','alert_modules_id'=>2,'active'=>1],
             ['name'=>'تعديل بدل الموظف','alert_modules_id'=>2,'active'=>1],
             ['name'=>'حذف بدل الموظف','alert_modules_id'=>2,'active'=>1],

             ['name'=>'اضافة نوع مكافئة','alert_modules_id'=>2,'active'=>1],
             ['name'=>'تعديل نوع مكافئة','alert_modules_id'=>2,'active'=>1],
             ['name'=>'حذف نوع مكافئة','alert_modules_id'=>2,'active'=>1],

             ['name'=>'اضافة خصومات','alert_modules_id'=>2,'active'=>1],
             ['name'=>'تعديل خصومات','alert_modules_id'=>2,'active'=>1],
             ['name'=>'حذف خصومات','alert_modules_id'=>2,'active'=>1],
            
             ['name'=>'اضافة نوع بدلات','alert_modules_id'=>2,'active'=>1],
             ['name'=>'تعديل نوع بدلات','alert_modules_id'=>2,'active'=>1],
             ['name'=>'حذف نوع بدلات','alert_modules_id'=>2,'active'=>1],

             ['name'=>'ارفاق ملف بصمة','alert_modules_id'=>3,'active'=>1],
             ['name'=>'تعديل متغيرات بصمة','alert_modules_id'=>3,'active'=>1],
             ['name'=>'تعديل بصمات يدويا','alert_modules_id'=>3,'active'=>1],

             ['name'=>'فتح شهر مالي','alert_modules_id'=>4,'active'=>1],
             ['name'=>'اغلاق وارشفة شهر مالي','alert_modules_id'=>4,'active'=>1],

             ['name'=>'اضافة جزاء ايام','alert_modules_id'=>4,'active'=>1],
             ['name'=>'تعديل جزاء ايام','alert_modules_id'=>4,'active'=>1],
             ['name'=>'حذف جزاء ايام','alert_modules_id'=>4,'active'=>1],

             ['name'=>'اضافة غياب ايام','alert_modules_id'=>4,'active'=>1],
             ['name'=>'تعديل غياب ايام','alert_modules_id'=>4,'active'=>1],
             ['name'=>'حذف غياب ايام','alert_modules_id'=>4,'active'=>1],
             
             ['name'=>'اضافة خصومات مالية','alert_modules_id'=>4,'active'=>1],
             ['name'=>'تعديل خصومات مالية','alert_modules_id'=>4,'active'=>1],
             ['name'=>'حذف خصومات مالية','alert_modules_id'=>4,'active'=>1],

             ['name'=>'اضافة سلف شهرية','alert_modules_id'=>4,'active'=>1],
             ['name'=>'تعديل سلف شهرية','alert_modules_id'=>4,'active'=>1],
             ['name'=>'حذف سلف شهرية','alert_modules_id'=>4,'active'=>1],

             ['name'=>'اضافة سلف مستديمة','alert_modules_id'=>4,'active'=>1],
             ['name'=>'تعديل سلف مستديمة','alert_modules_id'=>4,'active'=>1],
             ['name'=>'حذف سلف مستديمة','alert_modules_id'=>4,'active'=>1],

             ['name'=>'اضافة اضافي ايام','alert_modules_id'=>4,'active'=>1],
             ['name'=>'تعديل اضافي ايام','alert_modules_id'=>4,'active'=>1],
             ['name'=>'حذف اضافي ايام','alert_modules_id'=>4,'active'=>1],

             ['name'=>'اضافة مكافئات مالية','alert_modules_id'=>4,'active'=>1],
             ['name'=>'تعديل مكافئات مالية','alert_modules_id'=>4,'active'=>1],
             ['name'=>'حذف مكافئات مالية','alert_modules_id'=>4,'active'=>1],

             ['name'=>'اضافة بدلات متغيرة','alert_modules_id'=>4,'active'=>1],
             ['name'=>'تعديل بدلات متغيرة','alert_modules_id'=>4,'active'=>1],
             ['name'=>'حذف بدلات متغيرة','alert_modules_id'=>4,'active'=>1],

             ['name'=>'اضافة راتب يدوي','alert_modules_id'=>4,'active'=>1],
             ['name'=>'ايقاف راتب مؤقتاً','alert_modules_id'=>4,'active'=>1],
             ['name'=>'الغاء ايقاف راتب','alert_modules_id'=>4,'active'=>1],
             ['name'=>'ارشفة راتب بشكل مفرد','alert_modules_id'=>4,'active'=>1],
             ['name'=>'حذف راتب يدوي','alert_modules_id'=>4,'active'=>1],

             ['name'=>'تعديل رصيد السنوي يدوياً','alert_modules_id'=>5,'active'=>1],
             ['name'=>'تعديل رصيد السنوي تلقائي مع البصمة','alert_modules_id'=>5,'active'=>1],

             ['name'=>'اضافة تحقيقات ادارية','alert_modules_id'=>6,'active'=>1],
             ['name'=>'تعديل تحقيقات ادارية','alert_modules_id'=>6,'active'=>1],
             ['name'=>'حذف تحقيقات ادارية','alert_modules_id'=>6,'active'=>1],

             ['name'=>'تمييز سجل بمراقبة النظام','alert_modules_id'=>7,'active'=>1],
             ['name'=>'حذف سجل بمراقبة النظام','alert_modules_id'=>7,'active'=>1],

             ['name'=>'اضافة مستخدمين للنظام','alert_modules_id'=>8,'active'=>1],
             ['name'=>'تعديل مستخدمين النظام','alert_modules_id'=>8,'active'=>1],
             ['name'=>'حذف مستخدمين النظام','alert_modules_id'=>8,'active'=>1],

        
             



            ]);
            
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_movetype');
    }
};
