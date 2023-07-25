<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQcTestMethodIdInItemQcTestMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_qc_test_methods', function (Blueprint $table) {
            $table->bigInteger('qc_test_method_id')->nullable(true)->unsigned()->change();
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
            $table->bigInteger('qc_test_method_id')->nullable(true)->change();
        });
    }
}
