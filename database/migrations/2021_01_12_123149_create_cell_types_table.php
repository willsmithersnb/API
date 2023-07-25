<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellTypesTable extends Migration {

	public function up()
	{
		Schema::create('cell_types', function(Blueprint $table) {
			$table->softDeletes();
			$table->id();
			$table->timestamps();
			$table->string('name', 190);
		});
	}

	public function down()
	{
		Schema::drop('cell_types');
	}
}