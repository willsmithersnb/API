<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQcTestsTable extends Migration {

	public function up()
	{
		Schema::create('qc_tests', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
			$table->string('test_type', 80)->index();
			$table->bigInteger('price');
			$table->bigInteger('cost');
			$table->boolean('has_custom_value')->default(0);
		});
	}

	public function down()
	{
		Schema::drop('qc_tests');
	}
}