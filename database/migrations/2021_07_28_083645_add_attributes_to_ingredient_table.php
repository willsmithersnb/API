<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributesToIngredientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->smallInteger('pricing_unit')->default(3);
            $table->renameColumn('display_unit_type', 'unit_type');
            $table->bigInteger('price')->default(0);
            $table->bigInteger('cost')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('pricing_unit');
            $table->renameColumn('unit_type', 'display_unit_type');
            $table->dropColumn('price');
            $table->dropColumn('cost');
        });
    }
}
