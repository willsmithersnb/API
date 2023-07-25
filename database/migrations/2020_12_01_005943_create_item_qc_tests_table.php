<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemQcTestsTable extends Migration {

	public function up()
	{
		Schema::create('item_qc_tests', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('item_id')->unsigned();
			$table->bigInteger('qc_test_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('price');
			$table->bigInteger('cost');
			$table->jsonb('value')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('item_qc_tests');
	}
}