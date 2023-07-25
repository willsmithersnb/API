<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpressionTypesTable extends Migration {

	public function up()
	{
		Schema::create('expression_types', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 150);
		});
	}

	public function down()
	{
		Schema::drop('expression_types');
	}
}