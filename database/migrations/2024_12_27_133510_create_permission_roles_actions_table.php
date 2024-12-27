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
        Schema::create('permission_roles_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_sub_menu_id')->comment('permission_roles_sub_menu_id')->references('id')->on('permission_roles_sub_menu')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('permission_roles_id')->references('id')->on('permission_roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('permission_actions_id')->comment('permission_sub_menues_actions_id')->references('id')->on('permission_sub_menues_actions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('permission_roles_actions');
    }
};
