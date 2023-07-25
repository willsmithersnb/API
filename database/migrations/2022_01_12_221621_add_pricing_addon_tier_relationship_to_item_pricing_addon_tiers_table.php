<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricingAddonTierRelationshipToItemPricingAddonTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_pricing_addon_tiers', function (Blueprint $table) {
            $table->foreign('pricing_addon_tier_id')->references('id')->on('pricing_addon_tiers')
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
            $table->dropForeign('item_pricing_addon_tiers_pricing_addon_tier_id_foreign');
        });
    }
}
