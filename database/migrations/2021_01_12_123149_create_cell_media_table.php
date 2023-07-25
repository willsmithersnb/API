<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellMediaTable extends Migration {

	public function up()
	{
		Schema::create('cell_media', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 190);
		});
	}

	public function down()
	{
		Schema::drop('cell_media');
	}
}