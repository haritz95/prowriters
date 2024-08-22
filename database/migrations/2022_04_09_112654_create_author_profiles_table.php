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
        Schema::create('author_profiles', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->unsignedInteger('author_level_id')->nullable();
            $table->foreign('author_level_id')
                ->references('id')
                ->on('author_levels');

            $table->unsignedInteger('education_level_id')->nullable();
            $table->foreign('education_level_id')
                ->references('id')
                ->on('education_levels');

            $table->text('internal_note')->nullable();

            $table->longText('bio')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_details')->nullable();

            $table->string('blog_url')->nullable();
            $table->string('online_portfolio_url')->nullable();
            $table->string('linked_in_url')->nullable();

            $table->integer('years_of_experience')->default(0)->nullable();

            $table->string('resume')->nullable();

            $table->unsignedInteger('language_id_1')->nullable();
            $table->foreign('language_id_1')
                ->references('id')
                ->on('languages');

            $table->unsignedInteger('language_id_2')->nullable();
            $table->foreign('language_id_2')
                ->references('id')
                ->on('languages');

            $table->unsignedInteger('service_id_1')->nullable();
            $table->foreign('service_id_1')
                ->references('id')
                ->on('services');

            $table->unsignedInteger('service_id_2')->nullable();
            $table->foreign('service_id_2')
                ->references('id')
                ->on('services');

            $table->unsignedInteger('service_id_3')->nullable();
            $table->foreign('service_id_3')
                ->references('id')
                ->on('services');

            $table->unsignedInteger('subject_id_1')->nullable();
            $table->foreign('subject_id_1')
                ->references('id')
                ->on('subjects');

            $table->unsignedInteger('subject_id_2')->nullable();
            $table->foreign('subject_id_2')
                ->references('id')
                ->on('subjects');

            $table->unsignedInteger('subject_id_3')->nullable();
            $table->foreign('subject_id_3')
                ->references('id')
                ->on('subjects');

            $table->unsignedInteger('subject_id_4')->nullable();
            $table->foreign('subject_id_4')
                ->references('id')
                ->on('subjects');

            $table->unsignedInteger('subject_id_5')->nullable();
            $table->foreign('subject_id_5')
                ->references('id')
                ->on('subjects');

            $table->boolean('not_available_for_direct_order')->nullable();
            
            $table->boolean('not_available_for_bidding')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('author_profiles');
    }
};
