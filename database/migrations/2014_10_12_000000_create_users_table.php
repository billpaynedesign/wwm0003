<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->rememberToken();
            $table->string('shippingname')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('account')->nullable();
            $table->string('license_number')->nullable();
            $table->string('company')->nullable();
            $table->string('license_expire')->nullable();
            $table->boolean('admin')->nullable();
            $table->boolean('verified')->nullable();
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
		Schema::drop('users');
	}

}
