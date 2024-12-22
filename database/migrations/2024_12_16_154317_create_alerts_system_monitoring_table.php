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
        Schema::create('alerts_system_monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_modules_id')->nullable()->references('id')->on('alert_modules')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('alert_movetype_id')->nullable()->references('id')->on('alert_movetype')->onUpdate('cascade')->onDelete('cascade');
            $table->string('content',500);
            $table->bigInteger('foreign_key_table_id')->nullable();
            $table->bigInteger('employees_code')->nullable();
            $table->tinyInteger('is_marked')->default(0)->comment('هل مميز');
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->integer('com_code');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts_system_monitoring');
    }
};
