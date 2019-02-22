<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');

			$table->string('billing_address1')->nullable();
			$table->string('billing_address2')->nullable();
			$table->string('billing_city')->nullable();
			$table->string('billing_state')->nullable();
			$table->string('billing_zip')->nullable();

			$table->string('shipping_address1')->nullable();
			$table->string('shipping_address2')->nullable();
			$table->string('shipping_city')->nullable();
			$table->string('shipping_state')->nullable();
			$table->string('shipping_zip')->nullable();

            $table->string('email')->nullable();
            $table->string('rfq_num')->nullable();

            $table->enum('status',['Accepted','Open','Declined','Archived'])->default('Open');

            $table->float('total')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quotes');
    }
}
