<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderdetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orderdetails', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id');
			$table->integer('order_id');
			$table->integer('box_id')->nullable();
			$table->integer('quantity')->nullable();
			$table->string('subtotal')->nullable();
			$table->string('status')->nullable();
			$table->boolean('shipped')->nullable();
			$table->timestamp('shipped_date')->nullable();
			$table->boolean('paid')->nullable();
			$table->timestamp('paid_date')->nullable();
			$table->string('lot_number')->nullable();
			$table->string('expiration')->nullable();
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
		Schema::drop('orderdetails');
	}

}
