<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalUnitToItemSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_summaries', function (Blueprint $table) {
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
        Schema::table('item_summaries', function (Blueprint $table) {
            $table->dropColumn('total_units');
            $table->dropColumn('total_order_size');
        });
    }
}
