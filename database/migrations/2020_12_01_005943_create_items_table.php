<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration {

	public function up()
	{
		Schema::create('items', function(Blueprint $table) {
			$table->id();
			$table->nullableMorphs('itemable');
			$table->bigInteger('item_list_id')->unsigned();
			$table->bigInteger('customization_id')->unsigned()->nullable();
			$table->bigInteger('item_summary_id')->unsigned()->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('item_no');
			$table->string('name', 191);
			$table->bigInteger('price');
			$table->bigInteger('cost');
		});
	}

	public function down()
	{
		Schema::drop('items');
	}
}