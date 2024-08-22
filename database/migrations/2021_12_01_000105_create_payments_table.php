<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('number')->nullable();

            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('users');

            $table->string('method');
            $table->decimal('amount', config('database.decimal.total'), config('database.decimal.places'));
            $table->string('reference')->nullable();

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->text('internal_note')->nullable();
            $table->text('customer_note')->nullable();
            $table->dateTime('date')->nullable();

            $table->timestamps();

            $table->index(['method']);
            $table->index(['reference']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
