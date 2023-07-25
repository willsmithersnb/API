<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeIngredientsTable extends Migration {

	public function up()
	{
		Schema::create('grade_ingredients', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('grade_id')->unsigned()->index();
			$table->bigInteger('ingredient_id')->unsigned();
			$table->bigInteger('prestashop_id')->index();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('price')->default('0');
			$table->bigInteger('cost')->default('0');
			$table->double('pricing_quantity', 23,15)->default('1');
			$table->smallInteger('pricing_unit');
			$table->boolean('is_active')->default(1);
		});
	}

	public function down()
	{
		Schema::drop('grade_ingredients');
	}
}