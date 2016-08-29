<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBackorderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function(Blueprint $table)
        {
            $table->dropcolumn('backordered');
        });
        Schema::table('orderdetails', function(Blueprint $table)
        {
            $table->integer('backordered')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table)
        {
            $table->boolean('backordered')->nullable();
        });
        Schema::table('orderdetails', function(Blueprint $table)
        {
            $table->dropcolumn('backordered');
        });
    }
}
