<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterConditionalVariableOnItemPricingAddonTierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE item_pricing_addon_tiers DROP CONSTRAINT item_pricing_addon_tiers_conditional_variable_check');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $types = ['num_liters', 'num_units', 'num_packaging_options', 'num_ingredients_changed'];
        $result = join( ', ', array_map(function ($value){
            return "'$value'::character varying";
        }, $types));
        DB::statement("ALTER TABLE item_pricing_addon_tiers ADD CONSTRAINT item_pricing_addon_tiers_conditional_variable_check CHECK (conditional_variable::text = ANY (ARRAY[$result]::text[]))");
    }
}
