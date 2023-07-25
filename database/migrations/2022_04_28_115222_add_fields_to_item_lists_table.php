<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToItemListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_lists', function (Blueprint $table) {
            $table->bigInteger('price_per_liter')->default(0);
            $table->bigInteger('lead_time')->default(9);
            $table->bigInteger('total_units')->default(0);
            $table->bigInteger('total_order_size')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_lists', function (Blueprint $table) {
            $table->dropColumn('lead_time');
            $table->dropColumn('total_units');
            $table->dropColumn('total_order_size');
        });
    }
}
