<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableCustomerIdToPackagingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packaging_options', function (Blueprint $table) {
            $table->bigInteger('customer_id')->nullable();
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
        Schema::table('packaging_options', function (Blueprint $table) {
            $table->dropForeign('packaging_options_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
    }
}
