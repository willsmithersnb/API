<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagingOptionsTable extends Migration {

	public function up()
	{
		Schema::create('packaging_options', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
			$table->bigInteger('price');
			$table->bigInteger('cost');
			$table->string('packaging_type', 20);
			$table->boolean('has_custom_value');
			$table->double('max_fill_volume', 23,15);
			$table->smallInteger('pricing_unit');
			$table->smallInteger('pricing_unit_type');
		});
	}

	public function down()
	{
		Schema::drop('packaging_options');
	}
}