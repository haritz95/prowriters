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
        Schema::create('invoices', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid');
            $table->string('number')->nullable();

            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('users');

            $table->unsignedInteger('invoice_status_id');
            $table->foreign('invoice_status_id')
                ->references('id')
                ->on('invoice_statuses');

            $table->decimal('sub_total', config('database.decimal.total'), config('database.decimal.places'));

            // Discount
            $table->decimal('discount', config('database.decimal.total'), config('database.decimal.places'))
                ->default(0)
                ->nullable();

            $table->unsignedInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('coupon_code')->nullable();

            $table->decimal('coupon_discount', config('database.decimal.total'), config('database.decimal.places'))
                ->default(0)
                ->nullable();

            // Sales Tax Rate and Amount
            $table->decimal('sales_tax_rate', config('database.percentage.total'), config('database.percentage.places'))
                ->default(0)
                ->nullable();
            
            $table->decimal('sales_tax_amount', config('database.decimal.total'), config('database.decimal.places'))
                ->default(0)
                ->nullable();

            $table->decimal('total', config('database.decimal.total'), config('database.decimal.places'));

            $table->decimal('amount_paid', config('database.decimal.total'), config('database.decimal.places'))->default(0);

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->text('billing_address')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('customer_note')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->dateTime('due_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
