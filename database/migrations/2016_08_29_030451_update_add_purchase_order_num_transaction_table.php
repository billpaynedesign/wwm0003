<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddPurchaseOrderNumTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function ($table) {
                $table->string('purchase_order_num')->nullable();
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
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function ($table) {
                $table->dropColumn('purchase_order_num');
            });
        }
    }
}
