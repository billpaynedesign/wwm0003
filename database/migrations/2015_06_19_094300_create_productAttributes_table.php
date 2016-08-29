<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateproductAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('productAttributes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id');
			$table->string('name')->nullable();
			$table->string('option')->nullable();
			$table->float('price')->nullable();
			$table->float('msrp')->nullable();
			$table->float('discount')->nullable();
			$table->string('weight')->nullable();
			$table->string('reorderLevel')->nullable();
			$table->boolean('featured')->nullable();
			$table->boolean('active')->nullable();
			$table->integer('inStock')->nullable();
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
		Schema::drop('productAttributes');
	}

}
