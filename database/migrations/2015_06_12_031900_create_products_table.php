<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('category_id');
			$table->string('msrp')->nullable();
			$table->string('price')->nullable();
			$table->string('manufacturer')->nullable();
			$table->string('item_number')->nullable();
			$table->boolean('has_lot_expiry')->nullable();
			$table->text('short_description')->nullable();
			$table->text('description')->nullable();
			$table->text('note')->nullable();
			$table->string('picture')->nullable();
			$table->boolean('featured')->nullable();
			$table->boolean('active')->nullable();
			$table->string('slug')->nullable();
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
		Schema::drop('products');
	}

}
