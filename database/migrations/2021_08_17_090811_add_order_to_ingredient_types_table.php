<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToIngredientTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ingredient_types', function (Blueprint $table) {
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
        Schema::table('ingredient_types', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
