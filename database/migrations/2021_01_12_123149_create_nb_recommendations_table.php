<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNbRecommendationsTable extends Migration {

	public function up()
	{
		Schema::create('nb_recommendations', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->bigInteger('cell_type_id')->unsigned();
			$table->string('formula_name', 190);
		});
	}

	public function down()
	{
		Schema::drop('nb_recommendations');
	}
}