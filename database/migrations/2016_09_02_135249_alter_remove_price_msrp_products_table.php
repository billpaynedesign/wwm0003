<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRemovePriceMsrpProductsTable extends Migration
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
                $table->dropColumn('price');
                $table->dropColumn('msrp');
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
                $table->string('price');
                $table->string('msrp');
            });
        }
    }
}
