<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellMediaRecommendationsTable extends Migration {

	public function up()
	{
		Schema::create('cell_media_recommendations', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('recommendation_id')->unsigned();
			$table->bigInteger('cell_media_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('cell_media_recommendations');
	}
}