<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemListsTable extends Migration {

	public function up()
	{
		Schema::create('item_lists', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('coupon_id')->unsigned()->nullable();
			$table->morphs('item_listable');
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('net_total');
			$table->bigInteger('discount')->default('0');
			$table->float('discount_percentage', 3,2)->nullable()->default('0');
		});
	}

	public function down()
	{
		Schema::drop('item_lists');
	}
}