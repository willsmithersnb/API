<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogsTable extends Migration {

	public function up()
	{
		Schema::create('catalogs', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('number', 150);
			$table->bigInteger('product_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('catalogs');
	}
}