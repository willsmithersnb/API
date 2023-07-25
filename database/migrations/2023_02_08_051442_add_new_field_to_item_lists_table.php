<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldToItemListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_lists', function (Blueprint $table) {
            $table->renameColumn('net_total', 'gross_total');
            $table->bigInteger('adjustments')->unsigned()->nullable();
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
            $table->renameColumn('gross_total', 'net_total');
            $table->dropColumn('adjustments');
        });
    }
}
