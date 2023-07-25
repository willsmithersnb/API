<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddricingUnitTypeToFormulaIngredientMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formula_ingredient_materials', function (Blueprint $table) {
            $table->smallInteger('pricing_unit')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formula_ingredient_materials', function (Blueprint $table) {
            $table->dropColumn('pricing_unit');
        });
    }
}
