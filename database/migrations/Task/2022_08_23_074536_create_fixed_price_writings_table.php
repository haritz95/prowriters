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
        Schema::create('fixed_price_writings', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('assignment_id')->nullable();
            $table->foreign('assignment_id')
                ->references('id')
                ->on('assignments');            

            $table->longText('instruction')->nullable();

            //Financial
            $table->unsignedInteger('quantity')->nullable()->default(1);
            $table->decimal('amount', config('database.decimal.total'), config('database.decimal.places'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_price_writings');
    }
};
