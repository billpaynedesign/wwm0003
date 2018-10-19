<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsToVendorPoDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_po_details', function (Blueprint $table) {
            $table->string('reorder_number')->nullable();
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_po_details', function (Blueprint $table) {
            $table->dropColumn('reorder_number');
            $table->dropColumn('note');
        });
    }
}
