<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipForFormulaIngredientMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formula_ingredient_materials', function (Blueprint $table) {
            $table->foreign('material_id')->references('id')->on('materials')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('formula_ingredient_id')->references('id')->on('formula_ingredients')
                ->onDelete('restrict')
                ->onUpdate('cascade');
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
            $table->dropForeign('formula_ingredient_materials_material_id_foreign');
            $table->dropForeign('formula_ingredient_materials_formula_ingredient_id_foreign');
        });
    }
}
