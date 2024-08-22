<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urgencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('type', ['days', 'hours']);
            $table->integer('value');
            $table->enum('type_for_author', ['days', 'hours']);
            $table->integer('value_for_author');
            // Percentage to add to the price of the service.                    
            $table->decimal('percentage', config('database.percentage.total'), config('database.percentage.places'))->default(0)->nullable();

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
        Schema::dropIfExists('urgencies');
    }
}
