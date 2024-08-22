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
        Schema::create('tag_faq_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('faq_category_id');
            $table->foreign('faq_category_id')
                ->references('id')
                ->on('faq_categories')->onDelete('cascade');

            $table->unsignedInteger('faq_question_id');
            $table->foreign('faq_question_id', 'faq_id')
                ->references('id')
                ->on('faq_questions')->onDelete('cascade');

            $table->unique(['faq_category_id', 'faq_question_id'], 'faq_and_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_faq_categories');
    }
};
