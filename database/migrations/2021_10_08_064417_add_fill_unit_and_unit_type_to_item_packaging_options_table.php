<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFillUnitAndUnitTypeToItemPackagingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_packaging_options', function (Blueprint $table) {
            $table->smallInteger('fill_unit')->default(0);
            $table->smallInteger('unit_type')->default(0);
            $table->bigInteger('fill_tolerance')->default(0);
            $table->bigInteger('max_fill_volume')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_packaging_options', function (Blueprint $table) {
            $table->dropColumn('fill_unit');
            $table->dropColumn('unit_type');
            $table->dropColumn('fill_tolerance');
            $table->dropColumn('max_fill_volume');
        });
    }
}