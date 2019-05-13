<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingcartitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoppingcart_items', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('shopping_cart_id');
            $table->integer('product_id');
            $table->integer('uom_id');
            $table->integer('quantity');
            $table->string('cost');
            $table->string('sub_total');
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
        Schema::drop('shoppingcart_items');
    }
}
