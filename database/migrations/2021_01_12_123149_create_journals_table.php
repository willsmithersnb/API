<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalsTable extends Migration {

	public function up()
	{
		Schema::create('journals', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 150);
			$table->float('impact_factor', 8,4);
		});
	}

	public function down()
	{
		Schema::drop('journals');
	}
}