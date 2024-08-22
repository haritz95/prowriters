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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('service_type');
            $table->string('title');
            $table->string('stripe_id')->unique()->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', config('database.decimal.total'), config('database.decimal.places'))->nullable();
            $table->boolean('is_free')->nullable();
            $table->integer('number_of_characters_allowed_per_month')->nullable();
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
        Schema::dropIfExists('subscription_plans');
    }
};
