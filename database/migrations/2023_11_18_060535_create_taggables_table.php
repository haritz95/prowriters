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
        Schema::create('taggables', function (Blueprint $table) {

            $table->id('id');
            $table->uuid('uuid')->index();

            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');
            
            $table->unsignedBigInteger('tag_id');
            
            $table->index(['taggable_type', 'taggable_id']);                   
            $table->index('tag_id');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taggables');
    }
};
