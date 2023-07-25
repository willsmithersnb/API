<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulasTable extends Migration {

	public function up()
	{
		Schema::create('formulas', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('customer_id')->unsigned();
			$table->bigInteger('parent_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 150);
			$table->string('formula_hash', 60)->index();
		});
	}

	public function down()
	{
		Schema::drop('formulas');
	}
}