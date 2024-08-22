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
        Schema::create('academic_writings', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('assignment_id')->nullable();
            $table->foreign('assignment_id')
                ->references('id')
                ->on('assignments');

            $table->unsignedInteger('subject_id')->nullable();
            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects');

            $table->unsignedInteger('academic_level_id')->nullable();
            $table->foreign('academic_level_id')
                ->references('id')
                ->on('academic_levels');

            $table->string('number_of_words', 20)->nullable();
           

            $table->unsignedInteger('paper_format_id')->nullable();
            $table->foreign('paper_format_id')
                ->references('id')
                ->on('paper_formats');

            $table->unsignedInteger('number_of_sources')->nullable();

            $table->longText('instruction')->nullable();

           // Financial
           $table->unsignedInteger('quantity')->default(1)->nullable();
           $table->decimal('amount', config('database.decimal.total'), config('database.decimal.places'));          

            $table->decimal('unit_price', config('database.decimal.total'), config('database.decimal.places'))->nullable();
            $table->decimal('academic_level_price', config('database.decimal.total'), config('database.decimal.places'))->nullable();
            $table->decimal('subject_price', config('database.decimal.total'), config('database.decimal.places'))->nullable();
            $table->decimal('author_level_price', config('database.decimal.total'), config('database.decimal.places'))->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_writings');
    }
};
