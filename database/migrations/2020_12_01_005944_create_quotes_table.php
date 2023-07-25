<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration {

	public function up()
	{
		Schema::create('quotes', function(Blueprint $table) {
			$table->bigInteger('customer_id')->unsigned();
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
			$table->jsonb('custom_components')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('quotes');
	}
}