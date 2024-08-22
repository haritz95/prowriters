<?php

use App\Enums\CartType;
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
        Schema::create('user_carts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', CartType::all());
            $table->string('session_id')->nullable();
            $table->string('token');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->longText('items')->nullable();
            $table->decimal('sub_total', config('database.decimal.total'), config('database.decimal.places'));

            $table->decimal('discount', config('database.decimal.total'), config('database.decimal.places'))->default(0)->nullable();

            $table->unsignedInteger('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('coupon_discount', config('database.decimal.total'), config('database.decimal.places'))->default(0)->nullable();

            // Sales Tax Rate and Amount
            $table->decimal('sales_tax_rate', config('database.percentage.total'), config('database.percentage.places'))
                ->default(0)
                ->nullable();

            $table->decimal('sales_tax_amount', config('database.decimal.total'), config('database.decimal.places'))
                ->default(0)
                ->nullable();

            $table->decimal('total', config('database.decimal.total'), config('database.decimal.places'));

            $table->unsignedInteger('payment_id')->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
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
        Schema::dropIfExists('user_carts');
    }
};
