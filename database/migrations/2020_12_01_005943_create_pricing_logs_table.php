<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingLogsTable extends Migration {

	public function up()
	{
		Schema::create('pricing_logs', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('grade_ingredient_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('price')->default(0);
			$table->bigInteger('cost')->default(0);
			$table->double('pricing_quantity', 23,15)->default('1');
			$table->smallInteger('pricing_unit');
			$table->smallInteger('pricing_unit_type');
			$table->boolean('is_active')->default(1);
		});
	}

	public function down()
	{
		Schema::drop('pricing_logs');
	}
}