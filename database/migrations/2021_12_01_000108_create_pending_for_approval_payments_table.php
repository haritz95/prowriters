<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingForApprovalPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_for_approval_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('number')->nullable();
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('users');

            $table->unsignedInteger('offline_payment_method_id');
            $table->foreign('offline_payment_method_id')
                ->references('id')
                ->on('offline_payment_methods');

            $table->string('reference')->nullable();
            $table->decimal('amount', config('database.decimal.total'), config('database.decimal.places'));
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
        Schema::dropIfExists('pending_for_approval_payments');
    }
}
