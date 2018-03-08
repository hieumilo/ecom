<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuggestionImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggestion_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->integer('suggestion_id')->unsigned();
            $table->timestamps();

            $table->foreign('suggestion_id')
                ->references('id')->on('suggestions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suggestion_images');
    }
}
