<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 50);
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('amount', config('database.decimal.total'), config('database.decimal.places'));
            $table->dateTime('active_date');
            $table->dateTime('expiry_date')->nullable();
            $table->decimal('minimum_spend', config('database.decimal.total'), config('database.decimal.places'))->default(0)->nullable();
            $table->decimal('maximum_discount', config('database.decimal.total'), config('database.decimal.places'))->nullable();

            $table->unsignedInteger('usage')->default(0)->nullable();
            $table->unsignedInteger('usage_limit_per_coupon')->nullable();
            $table->unsignedInteger('usage_limit_per_user')->nullable();

            $table->boolean('specific_customer_only')->nullable();

            $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->boolean('first_order_only')->nullable();
            
            $table->boolean('inactive')->nullable();
            $table->boolean('archive')->nullable();
            $table->timestamps();

            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
