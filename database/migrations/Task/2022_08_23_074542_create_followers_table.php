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
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('task_id');        
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->unsignedInteger('user_id');        
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(array('task_id', 'user_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
};