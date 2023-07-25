<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_ingredients', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->string('name', 191);
            $table->string('cas_no', 191)->nullable();
            $table->string('manufacturer', 191);
            $table->string('catalog_no', 191)->nullable();
            $table->bigInteger('quantity')->unsigned();
            $table->smallInteger('quantity_unit');
            $table->smallInteger('unit_type');
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('formula_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_ingredients');
    }
}
