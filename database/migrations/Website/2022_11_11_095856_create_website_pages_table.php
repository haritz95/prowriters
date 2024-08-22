<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsitePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('website_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale', 2);
            $table->enum('type', ['system', 'custom'])->nullable();
            
            $table->string('slug')->nullable();
            $table->boolean('disable_auto_slug_gen')->nullable();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->enum('image_position', ['left', 'right', 'center'])->nullable();
            $table->text('appearance')->nullable();
            $table->text('additional_data')->nullable();
            $table->text('meta_tags')->nullable();
            $table->boolean('published')->nullable();

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->timestamps();
            // Indexing
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_pages');
    }
}
