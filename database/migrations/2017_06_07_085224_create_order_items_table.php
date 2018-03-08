<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->integer('product_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
                
            $table->foreign('order_id')
                ->references('id')->on('orders')
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
        Schema::dropIfExists('order_items');
    }
}
