<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToProductTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->boolean('customizable')->default(False);
            $table->boolean('liquid_enabled')->default(False);
            $table->boolean('powder_enabled')->default(False);
            $table->boolean('cgmp_enabled')->default(False);
            $table->boolean('vial_enabled')->default(False);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropColumn('customizable');
            $table->dropColumn('liquid_enabled');
            $table->dropColumn('powder_enabled');
            $table->dropColumn('cgmp_enabled');
            $table->dropColumn('vial_enabled');
        });
    }
}
