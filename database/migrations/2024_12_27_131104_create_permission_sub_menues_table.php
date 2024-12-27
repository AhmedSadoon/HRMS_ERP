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
        Schema::create('permission_sub_menues', function (Blueprint $table) {
            $table->id();
            $table->string('name',225);
            $table->tinyInteger('active')->default(1);
            $table->foreignId('permission_main_menues_id')->references('id')->on('permission_main_menus')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_sub_menues');
    }
};
