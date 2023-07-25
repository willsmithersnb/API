<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('maximum_order_quantity')->default(0)->nullable();
            $table->bigInteger('default_order_quantity')->default(0)->nullable();
            $table->boolean('is_customizable')->default(False)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('maximum_order_quantity');
            $table->dropColumn('default_order_quantity');
            $table->dropColumn('is_customizable');
        });
    }
}
