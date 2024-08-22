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
        Schema::create('bids', function (Blueprint $table) {
            
            $table->increments('id');
            $table->uuid('uuid');
            
            $table->unsignedInteger('bid_request_id')->nullable();
            $table->foreign('bid_request_id')
                ->references('id')
                ->on('bid_requests');
            
            $table->unsignedInteger('author_id')->nullable();
            $table->foreign('author_id')
                ->references('id')
                ->on('users');

            $table->unsignedInteger('duration_days')->nullable();
            $table->unsignedInteger('number_of_revisions')->nullable();
            
            $table->decimal('total', config('database.decimal.total'), config('database.decimal.places'));
            $table->decimal('platform_commission_rate', config('database.percentage.total'), config('database.percentage.places'));
            $table->decimal('author_payment_amount', config('database.decimal.total'), config('database.decimal.places'))->nullable();
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
        Schema::dropIfExists('bids');
    }
};
