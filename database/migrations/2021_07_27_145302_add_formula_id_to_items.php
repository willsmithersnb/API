<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormulaIdToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign('items_customization_id_foreign');
            $table->renameColumn('customization_id', 'formula_id');

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
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign('items_formula_id_foreign');
            $table->renameColumn('formula_id', 'customization_id');

            $table->foreign('customization_id')->references('id')->on('formulas')
            ->onDelete('restrict')
            ->onUpdate('cascade');
            
        });

    }
}
