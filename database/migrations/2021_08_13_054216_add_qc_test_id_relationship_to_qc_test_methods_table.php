<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQcTestIdRelationshipToQcTestMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_test_methods', function (Blueprint $table) {
            $table->foreign('qc_test_id')->references('id')->on('qc_tests')
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
        Schema::table('qc_test_methods', function (Blueprint $table) {
            $table->dropForeign('qc_test_methods_qc_test_id_foreign');
        });
    }
}
