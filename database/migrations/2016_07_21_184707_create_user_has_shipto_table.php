<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasShiptoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_shiptos', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('address1');
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->timestamps();
        });
        Schema::table('users', function(Blueprint $table)
        {
            if (Schema::hasColumn('users', 'shippingname')) {
                $table->dropColumn('shippingname');
            }
            if (Schema::hasColumn('users', 'address1')) {
                $table->dropColumn('address1');
            }
            if (Schema::hasColumn('users', 'address2')) {
                $table->dropColumn('address2');
            }
            if (Schema::hasColumn('users', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('users', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('users', 'zip')) {
                $table->dropColumn('zip');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_shiptos');
    }
}
