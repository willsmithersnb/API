<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterConditionalVariableOnPricingAddonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE pricing_addons DROP CONSTRAINT pricing_addons_conditional_variable_check');
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
        DB::statement("ALTER TABLE pricing_addons ADD CONSTRAINT pricing_addons_conditional_variable_check CHECK (conditional_variable::text = ANY (ARRAY[$result]::text[]))");
    }
}
