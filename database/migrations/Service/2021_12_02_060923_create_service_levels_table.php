<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['fixed', 'percentage'])->default('fixed');
            $table->boolean('is_default')->nullable();
            $table->decimal('price', config('database.decimal.total'), config('database.decimal.places'))->default(0)->nullable();
            $table->string('name');     
            $table->text('description')->nullable();            
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
        Schema::dropIfExists('service_levels');
    }
}
