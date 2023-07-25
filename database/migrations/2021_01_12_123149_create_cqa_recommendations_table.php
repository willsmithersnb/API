<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCqaRecommendationsTable extends Migration {

	public function up()
	{
		Schema::create('cqa_recommendations', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('recommendation_id')->unsigned();
			$table->bigInteger('critical_quality_attribute_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('cqa_recommendations');
	}
}