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
        Schema::create('tasks', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid');
            $table->string('number')->nullable();

            $table->unsignedInteger('details_id')->nullable();
            $table->string('details_type')->nullable();

            $table->unsignedInteger('service_id');
            $table->foreign('service_id')
                ->references('id')
                ->on('services');            

            $table->unsignedInteger('project_id')->nullable();
            $table->foreign('project_id')
                ->references('id')
                ->on('projects');

            $table->string('title')->nullable();

            $table->unsignedInteger('task_status_id')->nullable();
            $table->foreign('task_status_id')
                ->references('id')
                ->on('task_statuses');

            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('users');        

            $table->unsignedInteger('author_level_id')->nullable();
            $table->foreign('author_level_id')
                ->references('id')
                ->on('author_levels');

            $table->datetime('dead_line')->nullable();
            $table->datetime('dead_line_for_author')->nullable();

            $table->unsignedInteger('author_id')->nullable();
            $table->foreign('author_id')
                ->references('id')
                ->on('users');
            
            $table->unsignedInteger('editor_id')->nullable();
            $table->foreign('editor_id')
                ->references('id')
                ->on('users');

            $table->boolean('update_via_sms')->nullable();

            $table->integer('revisions_allowed')->nullable();
            $table->integer('revisions_taken')->default(0)->nullable();
         
            $table->decimal('additional_services_price', config('database.decimal.total'), config('database.decimal.places'));
            
            $table->boolean('is_total_overridden')->nullable();
            $table->decimal('original_total', config('database.decimal.total'), config('database.decimal.places'))->nullable();
            $table->decimal('total', config('database.decimal.total'), config('database.decimal.places'))->nullable();
            $table->decimal('author_payment_amount', config('database.decimal.total'), config('database.decimal.places'))->nullable();

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            
            

            $table->unsignedInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices');

            $table->boolean('is_archived_for_admin')->nullable();
            $table->boolean('is_archived_for_customer')->nullable();
            $table->boolean('is_archived_for_author')->nullable();

            $table->boolean('is_billed')->nullable();
            $table->datetime('accepted_at')->nullable();
            
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
        Schema::dropIfExists('tasks');
    }
};
