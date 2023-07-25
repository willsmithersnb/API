<?php

use App\Models\Ingredient;
use App\Models\IngredientType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIngredientTypeRelationshipToIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->bigInteger('ingredient_type_id')->unsigned()->nullable();
            $table->foreign('ingredient_type_id')->references('id')->on('ingredient_types')
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
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropForeign('ingredients_ingredient_type_id_foreign');
            $table->dropColumn('ingredient_type_id');
        });
    }
}
