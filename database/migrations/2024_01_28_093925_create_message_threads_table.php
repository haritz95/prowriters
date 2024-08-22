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
        Schema::create('message_threads', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('number')->nullable();
            
            $table->string('subject')->nullable();

            $table->unsignedInteger('recipient_id');
            $table->foreign('recipient_id')
                ->references('id')
                ->on('users');
            
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->boolean('is_archived')->nullable();
              

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_threads');
    }
};
