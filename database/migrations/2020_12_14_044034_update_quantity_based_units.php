<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateQuantityBasedUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formula_ingredients', function (Blueprint $table) {
            $table->bigInteger('quantity')->unsigned()->change();
        });
        Schema::table('grade_ingredients', function (Blueprint $table) {
            $table->bigInteger('pricing_quantity')->unsigned()->change();
        });
        Schema::table('ingredients', function (Blueprint $table) {
            $table->bigInteger('min_quantity')->unsigned()->change();
            $table->bigInteger('max_quantity')->unsigned()->change();
        });
        Schema::table('packaging_options', function (Blueprint $table) {
            $table->bigInteger('max_fill_volume')->unsigned()->change();
        });
        Schema::table('pricing_logs', function (Blueprint $table) {
            $table->bigInteger('pricing_quantity')->unsigned()->change();
        });
        Schema::table('item_packaging_options', function (Blueprint $table) {
            $table->bigInteger('fill_amount')->unsigned()->change();
            $table->bigInteger('quantity')->unsigned()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE formula_ingredients MODIFY quantity DOUBLE(23, 15) DEFAULT 0');
        DB::statement('ALTER TABLE grade_ingredients MODIFY pricing_quantity DOUBLE(23, 15) DEFAULT 0');
        DB::statement('ALTER TABLE ingredients MODIFY min_quantity DOUBLE(23, 15) DEFAULT 0');
        DB::statement('ALTER TABLE ingredients MODIFY max_quantity DOUBLE(23, 15) DEFAULT 0');
        DB::statement('ALTER TABLE packaging_options MODIFY max_fill_volume DOUBLE(23, 15) DEFAULT 0');
        DB::statement('ALTER TABLE pricing_logs MODIFY pricing_quantity DOUBLE(23, 15) DEFAULT 0');
        DB::statement('ALTER TABLE item_packaging_options MODIFY fill_amount DOUBLE(23, 15) DEFAULT 0');
        DB::statement('ALTER TABLE item_packaging_options MODIFY quantity DOUBLE(23, 15) DEFAULT 0');
        
    }
}
