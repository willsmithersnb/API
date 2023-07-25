<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name', 191)->change();
            $table->string('supplier_name', 191)->change();
            $table->bigInteger('lead_time')->default('0')->change();
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
            $table->string('name', 150)->change();
            $table->string('supplier_name', 150)->change();
            $table->smallInteger('lead_time')->default('7')->change();
        });
    }
}
