<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemRelationshipToItemPricingAddonTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_pricing_addon_tiers', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')
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
        Schema::table('item_pricing_addon_tiers', function (Blueprint $table) {
            $table->dropForeign('item_pricing_addon_tiers_item_id_foreign');
        });
    }
}
