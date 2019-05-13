<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQbIdProductsUsersOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function ($table) {
                $table->integer('qb_id')->nullable();
            });
        }
        if (Schema::hasTable('users')) {
            Schema::table('users', function ($table) {
                $table->integer('qb_id')->nullable();
            });
        }
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function ($table) {
                $table->integer('qb_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function ($table) {
                $table->dropColumn('qb_id');
            });
        }
        if (Schema::hasTable('users')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('qb_id')->nullable();
            });
        }
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function ($table) {
                $table->dropColumn('qb_id')->nullable();
            });
        }
    }
}
