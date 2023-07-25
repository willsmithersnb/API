<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormulaRelationshipToCustomIngredients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_ingredients', function (Blueprint $table) {
            $table->foreign('formula_id')->references('id')->on('formulas')
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
        Schema::table('custom_ingredients', function (Blueprint $table) {
            $table->dropForeign('custom_ingredients_formula_id_foreign');
        });
    }
}
