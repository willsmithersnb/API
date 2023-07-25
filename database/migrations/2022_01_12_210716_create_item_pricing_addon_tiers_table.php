<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPricingAddonTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_pricing_addon_tiers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('pricing_addon_id')->unsigned();
            $table->bigInteger('pricing_addon_tier_id')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->enum('conditional_variable', ['num_liters', 'num_units', 'num_packaging_options']);
            $table->string('name',150);
            $table->enum('pricing_type', ['linear', 'variable']);
            $table->enum('cost_type', ['linear', 'variable']);
            $table->boolean('is_customer_visible');
            $table->boolean('is_enabled');
            $table->bigInteger('condition_greater_than');            
            $table->bigInteger('price');
            $table->bigInteger('cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_pricing_addon_tiers');
    }
}
