<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendationsTable extends Migration {

	public function up()
	{
		Schema::create('recommendations', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('cell_type_id')->unsigned();
			$table->bigInteger('ingredient_id')->unsigned();
			$table->enum('deviation', array('increase', 'decrease', 'maintain'));
			$table->bigInteger('concentration_high')->unsigned()->nullable();
			$table->bigInteger('concentration_mid')->unsigned()->nullable();
			$table->bigInteger('concentration_low')->unsigned()->nullable();
			$table->smallInteger('concentration_unit');
			$table->string('score');
			$table->text('quote');
			$table->bigInteger('research_paper_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('recommendations');
	}
}