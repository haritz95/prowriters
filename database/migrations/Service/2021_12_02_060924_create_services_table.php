<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');          
            $table->uuid('uuid');   
            $table->unsignedInteger('service_type_id');
            
            $table->string('slug');     
            $table->string('name');                              
            $table->text('description')->nullable();                              
            $table->string('image')->nullable();  
            $table->string('unit_name')->nullable();
            $table->string('assignment_label')->nullable();
            $table->unsignedInteger('minimum_order_quantity')->nullable();  
            $table->string('allowed_file_extensions')->nullable();

            $table->unsignedInteger('maximum_file_size')->nullable();
            $table->unsignedInteger('minimum_number_of_files_to_upload')->nullable();
            $table->unsignedInteger('maximum_number_of_files_to_upload')->nullable();                  

            $table->decimal('commission', config('database.percentage.total'), config('database.percentage.places'))->default(0)->nullable();
            $table->decimal('commission_from_bid', config('database.percentage.total'), config('database.percentage.places'))->default(0)->nullable();

            // $table->boolean('disable_writing')->nullable();
            // $table->boolean('disable_editing')->nullable();
            // $table->boolean('disable_proofreading')->nullable();
            
            $table->boolean('inactive')->nullable();
            $table->boolean('not_available_for_direct_order')->nullable();
            $table->boolean('not_available_for_bidding')->nullable();
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
        Schema::dropIfExists('services');
    }
}
