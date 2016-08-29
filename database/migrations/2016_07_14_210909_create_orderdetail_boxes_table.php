<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderdetailBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderdetail_boxes', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->string('label')->nullable();
            $table->string('tracking')->nullable();
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
        Schema::drop('orderdetail_boxes');
    }
}
