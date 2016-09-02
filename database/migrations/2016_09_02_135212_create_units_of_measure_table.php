<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsOfMeasureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units_of_measure', function($table)
        {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('name');
            $table->string('price')->nullable();
            $table->string('msrp')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('units_of_measure');
    }
}
