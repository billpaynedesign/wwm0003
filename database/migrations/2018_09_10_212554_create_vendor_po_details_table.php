<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_po_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_purchase_order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->integer('quantity');
            $table->float('item_total');
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
        Schema::drop('vendor_po_details');
    }
}
