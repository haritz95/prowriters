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
        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('slug');
            $table->string('name');

            $table->unsignedInteger('quantity');

            $table->unsignedInteger('assignment_id')->nullable();
            $table->foreign('assignment_id')
                ->references('id')
                ->on('assignments');

            $table->unsignedInteger('urgency_id')->nullable();
            $table->foreign('urgency_id')
                ->references('id')
                ->on('urgencies');

            $table->decimal('price', config('database.decimal.total'), config('database.decimal.places'));

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
        Schema::dropIfExists('units');
    }
};
