<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration {

	public function up()
	{
		Schema::create('addresses', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('customer_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->string('line_1', 191);
			$table->string('line_2', 191)->nullable();
			$table->string('city', 191);
			$table->string('state', 191)->nullable();
			$table->string('zip_code', 10);
			$table->string('country', 100);
		});
	}

	public function down()
	{
		Schema::drop('addresses');
	}
}