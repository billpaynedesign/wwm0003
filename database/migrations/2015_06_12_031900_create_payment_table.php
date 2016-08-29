<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type');
			$table->boolean('allowed');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment');
	}

}
