<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGpeRecommendationsTable extends Migration {

	public function up()
	{
		Schema::create('gpe_recommendations', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('recommendation_id')->unsigned();
			$table->bigInteger('gene_protein_expression_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('gpe_recommendations');
	}
}