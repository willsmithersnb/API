<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPackagingOptionsTable extends Migration {

	public function up()
	{
		Schema::create('item_packaging_options', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('item_id')->unsigned();
			$table->bigInteger('packaging_option_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('price');
			$table->bigInteger('cost');
			$table->double('fill_amount', 23,15);
			$table->double('quantity', 23,15);
			$table->jsonb('value')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('item_packaging_options');
	}
}