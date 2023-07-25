<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderColumnToQcTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_tests', function (Blueprint $table) {
            $table->unsignedSmallInteger('order', false)->nullable(false)->default(32767);
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
            $table->dropColumn('order');
        });
    }
}
