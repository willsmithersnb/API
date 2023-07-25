<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQcTestMethodRelationshipToItemQcTestMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_qc_test_methods', function (Blueprint $table) {
            $table->foreign('qc_test_method_id')->references('id')->on('qc_test_methods')
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
        Schema::table('item_qc_test_methods', function (Blueprint $table) {
            $table->dropForeign('item_qc_test_methods_qc_test_method_id_foreign');
        });
    }
}
