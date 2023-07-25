<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropGradeIngredientFromFormulaIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formula_ingredients', function (Blueprint $table) {
            $table->dropForeign('formula_ingredients_grade_ingredient_id_foreign');
            $table->dropColumn('grade_ingredient_id');
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
            $table->bigInteger('grade_ingredient_id')->unsigned();
            $table->foreign('grade_ingredient_id')->references('id')->on('grade_ingredients')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }
}
