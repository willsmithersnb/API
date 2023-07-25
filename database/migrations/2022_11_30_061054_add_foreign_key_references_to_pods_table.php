<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyReferencesToPodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pods', function (Blueprint $table) {
            $table->foreign('item_packaging_option_id')->references('id')->on('item_packaging_options')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')
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
        Schema::table('pods', function (Blueprint $table) {
            $table->dropForeign('pods_item_packaging_option_id_foreign');
            $table->dropForeign('pods_customer_id_foreign');
        });
    }
}
