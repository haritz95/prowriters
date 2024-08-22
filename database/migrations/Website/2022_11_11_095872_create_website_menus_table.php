<?php

use App\Models\Website\WebsiteMenu;
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
        Schema::create('website_menus', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id')
                ->references('id')
                ->on('website_menus')
                ->onDelete('cascade');

            $table->string('locale', 2)->nullable();
            $table->enum('position', [WebsiteMenu::POSITION_TOP, WebsiteMenu::POSITION_FOOTER])->nullable();
            $table->string('name');
            $table->integer('sequence_number')->nullable();

            $table->unsignedInteger('website_page_id')->nullable();
            $table->foreign('website_page_id')
                ->references('id')
                ->on('website_pages')
                ->onDelete('cascade');

            $table->boolean('inactive')->nullable();
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
        Schema::dropIfExists('website_menus');
    }
};
