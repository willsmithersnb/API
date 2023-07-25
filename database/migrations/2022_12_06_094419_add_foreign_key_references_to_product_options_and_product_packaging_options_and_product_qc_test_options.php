<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyReferencesToProductOptionsAndProductPackagingOptionsAndProductQcTestOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        Schema::table('product_packaging_options', function (Blueprint $table) {
            $table->foreign('product_option_id')->references('id')->on('product_options')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('packaging_option_id')->references('id')->on('packaging_options')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        Schema::table('product_qc_tests', function (Blueprint $table) {
            $table->foreign('product_option_id')->references('id')->on('product_options')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('qc_test_id')->references('id')->on('qc_tests')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('qc_test_method_id')->references('id')->on('qc_test_methods')
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
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropForeign('product_options_product_id_foreign');
        });

        Schema::table('product_packaging_options', function (Blueprint $table) {
            $table->dropForeign('product_packaging_options_product_option_id_foreign');
            $table->dropForeign('product_packaging_options_packaging_option_id_foreign');
        });

        Schema::table('product_qc_tests', function (Blueprint $table) {
            $table->dropForeign('product_qc_tests_product_option_id_foreign');
            $table->dropForeign('product_qc_tests_qc_test_id_foreign');
            $table->dropForeign('product_qc_tests_qc_test_method_id_foreign');
        });
    }
}
