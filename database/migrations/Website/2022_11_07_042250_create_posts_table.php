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
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale', 2)->default('en');
            $table->string('slug');
            $table->string('title');
            $table->string('author_name');
            $table->string('thumbnail_image')->nullable();
            $table->string('thumbnail_image_alt_title')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('cover_image_alt_title')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->text('meta_tags')->nullable();
            $table->boolean('published')->nullable();
            $table->boolean('disable_auto_slug_gen')->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('posts');
    }
};
