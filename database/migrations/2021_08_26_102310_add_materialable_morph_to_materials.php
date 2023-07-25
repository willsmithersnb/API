<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialableMorphToMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->nullableMorphs('materialable');
            $table->dropForeign('materials_ingredient_id_foreign');
            $table->dropColumn('ingredient_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('materialable_id');
            $table->dropColumn('materialable_type');
            $table->bigInteger('ingredient_id')->nullable(true);
            $table->foreign('ingredient_id')->references('id')->on('ingredients')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }
}
