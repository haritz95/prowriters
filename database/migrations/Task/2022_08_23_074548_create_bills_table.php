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
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('number');
            $table->string('invoice_number')->nullable();
            
            $table->unsignedInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users');
            
            $table->decimal('total', config('database.decimal.total'), config('database.decimal.places'));
            $table->string('name');
            $table->string('address')->nullable();
            $table->text('note')->nullable();

            $table->date('paid')->nullable();
            $table->string('payment_reference')->nullable();
            $table->unsignedInteger('paid_by_user_id')->nullable();
            $table->foreign('paid_by_user_id')->references('id')->on('users');

            $table->boolean('is_archived_for_admin')->nullable();            
            $table->boolean('is_archived_for_author')->nullable();

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
        Schema::dropIfExists('bills');
    }
};
