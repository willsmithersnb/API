<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulaIngredientsTable extends Migration {

	public function up()
	{
		Schema::create('formula_ingredients', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('formula_id')->unsigned();
			$table->bigInteger('grade_ingredient_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->double('quantity', 23,15);
			$table->smallInteger('quantity_unit');
			$table->smallInteger('pricing_unit');
			$table->smallInteger('unit_type');
			$table->bigInteger('price');
			$table->bigInteger('cost');
		});
	}

	public function down()
	{
		Schema::drop('formula_ingredients');
	}
}