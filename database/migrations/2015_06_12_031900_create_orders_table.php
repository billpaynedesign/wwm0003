<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('shippingname')->nullable();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('address1')->nullable();
			$table->string('address2')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('zip')->nullable();
			$table->string('phone')->nullable();
			$table->string('secondary_phone')->nullable();
			$table->integer('transaction_id')->nullable();
			$table->timestamp('orderDate')->nullable();
			$table->timestamp('shipDate')->nullable();
			$table->timestamp('requestDate')->nullable();
			$table->string('transactionStatus')->nullable();
			$table->string('shipStatus')->nullable();
			$table->boolean('paid')->nullable();
			$table->timestamp('paymentDate')->nullable();
			$table->string('total')->nullable();
			$table->string('token')->nullable();
			$table->boolean('backordered')->nullable();
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
		Schema::drop('orders');
	}

}
