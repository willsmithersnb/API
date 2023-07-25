<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyConstraintToRecommendedProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recommended_products', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('parent_id')->references('id')->on('products')
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
        Schema::table('recommended_products', function (Blueprint $table) {
            $table->dropForeign('recommended_products_product_id_foreign');
            $table->dropForeign('recommended_products_parent_id_foreign');
        });
    }
}
