<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceAndCostToCustomIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_ingredients', function (Blueprint $table) {
            $table->bigInteger('price')->nullable();
            $table->bigInteger('cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_ingredients', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('cost');
        });
    }
}
