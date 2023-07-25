<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductTypeIdAndProductIdToItemSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_summaries', function (Blueprint $table) {
            $table->bigInteger('product_type_id')->unsigned()->nullable();
            $table->foreign('product_type_id')->references('id')->on('product_types')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_summaries', function (Blueprint $table) {
            $table->dropForeign('items_summaries_product_type_id_foreign');
            $table->dropForeign('items_summaries_product_id_foreign');
            $table->dropColumn('product_type_id');
            $table->dropColumn('product_id');
        });
    }
}
