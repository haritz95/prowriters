<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('slug');
            $table->string('name')->nullable();
            $table->decimal('percentage', config('database.percentage.total'), config('database.percentage.places'))->default(0)->nullable();
            
            // $table->boolean('is_default_for_content_writing')->nullable();
            // $table->boolean('available_for_content_writing')->nullable();
            // $table->boolean('available_for_translation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
