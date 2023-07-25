<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQcTestMethodIdToItemQcTestMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_qc_test_methods', function (Blueprint $table) {
            $table->bigInteger('qc_test_method_id');
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
            $table->dropColumn('qc_test_method_id');
        });
    }
}
