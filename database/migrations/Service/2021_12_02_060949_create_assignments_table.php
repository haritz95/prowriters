<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('slug')->unique();
            $table->unsignedInteger('service_id')->index();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->string('name');

            $table->string('description')->nullable();
            $table->text('deliverables')->nullable();
            $table->decimal('price', config('database.decimal.total'), config('database.decimal.places'))->default(0)->nullable();
            $table->string('unit_name')->nullable();
            
            $table->unsignedInteger('author_level_id')->nullable();
            $table->foreign('author_level_id')
                ->references('id')
                ->on('author_levels');

            $table->decimal('author_payment_amount', config('database.decimal.total'), config('database.decimal.places'))->nullable();

            $table->unsignedInteger('urgency_id')->nullable();
            $table->foreign('urgency_id')
                ->references('id')
                ->on('urgencies');

            $table->string('number_of_revisions_allowed')->nullable();

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
        Schema::dropIfExists('assignments');
    }
}
