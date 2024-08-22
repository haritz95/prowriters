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
        Schema::create('system_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso_code', 2);
            $table->string('name')->nullable();
            $table->string('country_code', 2);
            $table->enum('layout_direction', ['ltr', 'rtl'])->default('ltr');
            $table->boolean('is_default')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_languages');
    }
};
