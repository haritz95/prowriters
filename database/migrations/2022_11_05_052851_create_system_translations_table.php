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
        Schema::create('system_translations', function (Blueprint $table) {
            $table->increments('id');    
            $table->string('locale');

            $table->unsignedInteger('system_text_id');
            $table->foreign('system_text_id')
                ->references('id')
                ->on('system_texts')->onDelete('cascade');
            
            $table->text('translated_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_translations');
    }
};
