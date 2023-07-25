<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('customer_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}