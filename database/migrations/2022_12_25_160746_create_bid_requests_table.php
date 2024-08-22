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
        Schema::create('bid_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('number')->nullable();

            $table->unsignedInteger('task_id')->nullable();
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks');

            $table->decimal('budget', config('database.decimal.total'), config('database.decimal.places'));

            $table->unsignedInteger('bid_request_status_id')->nullable();
            $table->foreign('bid_request_status_id')
                ->references('id')
                ->on('bid_request_statuses');

            $table->boolean('archive_for_customer')->nullable();
            $table->boolean('archive_for_admin')->nullable();
            $table->boolean('archive_for_author')->nullable();

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
        Schema::dropIfExists('bid_requests');
    }
};
