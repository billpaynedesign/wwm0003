<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug');
			$table->string('name');
			$table->longtext('description')->nullable();
			$table->string('picture')->nullable();
			$table->boolean('active')->default(1);
			$table->boolean('featured')->nullable();
			$table->timestamps();
			$table->integer('parent_id')->nullable();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');
	}

}
