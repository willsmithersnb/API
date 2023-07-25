<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIngredientRelationshipToFormulaIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formula_ingredients', function (Blueprint $table) {
            $table->foreign('ingredient_id')->references('id')->on('ingredients')
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
        Schema::table('formula_ingredients', function (Blueprint $table) {
            $table->dropForeign('formula_ingredients_ingredient_id_foreign');
        });
    }
}
