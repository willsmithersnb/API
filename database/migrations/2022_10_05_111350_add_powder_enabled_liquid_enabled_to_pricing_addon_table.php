<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPowderEnabledLiquidEnabledToPricingAddonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_addons', function (Blueprint $table) {
            $table->boolean('liquid_enabled')->default(False);
            $table->boolean('powder_enabled')->default(False);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_addons', function (Blueprint $table) {
            $table->dropColumn('liquid_enabled');
            $table->dropColumn('powder_enabled');
        });
    }
}
