<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressRelationshipToQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->foreign('billing_address_id')->references('id')->on('addresses')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('shipping_address_id')->references('id')->on('addresses')
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
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign('quotes_billing_address_id_foreign');
            $table->dropForeign('quotes_shipping_address_id_foreign');
        });
    }
}
