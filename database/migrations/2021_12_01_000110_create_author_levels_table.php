<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('slug');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_popular')->nullable();
            $table->boolean('is_default')->nullable();
            $table->unsignedInteger('numeric_value')->nullable();

            $table->decimal('percentage', config('database.percentage.total'), config('database.percentage.places'))->default(0)->nullable();

            $table->integer('number_of_tasks_at_a_time')->nullable();

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
        Schema::dropIfExists('author_levels');
    }
}
