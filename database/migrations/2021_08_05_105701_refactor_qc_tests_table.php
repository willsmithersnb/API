<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorQcTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_tests', function (Blueprint $table) {
            $table->dropColumn('test_type');
            $table->dropColumn('price');
            $table->dropColumn('cost');
            $table->dropColumn('has_custom_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qc_tests', function (Blueprint $table) {
            $table->string('test_type', 80)->index();
            $table->bigInteger('price');
            $table->bigInteger('cost');
            $table->boolean('has_custom_value')->default(0);
        });
    }
}
