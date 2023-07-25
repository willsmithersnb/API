<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingRulesTable extends Migration {

	public function up()
	{
		Schema::create('pricing_rules', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
			$table->bigInteger('price');
			$table->bigInteger('cost');
			$table->string('condition', 191);
			$table->boolean('has_custom_price');
		});
	}

	public function down()
	{
		Schema::drop('pricing_rules');
	}
}