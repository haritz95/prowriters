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
        Schema::create('task_additional_services', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('task_id');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')->onDelete('cascade');

            $table->unsignedInteger('additional_service_id');
            $table->foreign('additional_service_id')
                ->references('id')
                ->on('additional_services');

            $table->unsignedInteger('quantity');
            $table->decimal('price', config('database.decimal.total'), config('database.decimal.places'));
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_additional_services');
    }
};
