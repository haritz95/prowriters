<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            
            $table->string('number')->nullable();

            $table->enum('type', ['add', 'deduct']);
            $table->decimal('amount', 10, 4);
            $table->string('description')->nullable();

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->unsignedInteger('adjuster_id');
            $table->foreign('adjuster_id')
                ->references('id')
                ->on('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_adjustments');
    }
}
