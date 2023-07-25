<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCartsTable extends Migration {

	public function up()
	{
		Schema::create('carts', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('customer_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
			$table->jsonb('custom_components')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('carts');
	}
}