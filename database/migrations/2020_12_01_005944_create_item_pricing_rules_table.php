<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPricingRulesTable extends Migration {

	public function up()
	{
		Schema::create('item_pricing_rules', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('pricing_rule_id')->unsigned();
			$table->bigInteger('item_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('price');
			$table->bigInteger('cost');
		});
	}

	public function down()
	{
		Schema::drop('item_pricing_rules');
	}
}