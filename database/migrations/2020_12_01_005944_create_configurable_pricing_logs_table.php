<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurablePricingLogsTable extends Migration {

	public function up()
	{
		Schema::create('configurable_pricing_logs', function(Blueprint $table) {
			$table->id();
			$table->morphs('legible');
			$table->bigInteger('user_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('price');
			$table->jsonb('before_obj');
		});
	}

	public function down()
	{
		Schema::drop('configurable_pricing_logs');
	}
}