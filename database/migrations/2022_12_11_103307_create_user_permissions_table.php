<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->index();
            $table->uuid('permission_owner')->index();
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
        });

        Schema::table('user_permissions', function (Blueprint $table) {
           $table->foreign('user_id')->references('id')->on('users')
               ->onDelete('cascade');
       });

       Schema::table('user_permissions', function (Blueprint $table) {
           $table->foreign('role_id')->references('id')->on('user_roles')
               ->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
};
