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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('invoice_id');
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')->onDelete('cascade');

            $table->string('invoiceable_type')->nullable();
            $table->unsignedInteger('invoiceable_id')->nullable();

            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', config('database.decimal.total'), config('database.decimal.places'));
            $table->unsignedInteger('quantity');
            $table->decimal('sub_total', config('database.decimal.total'), config('database.decimal.places'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
