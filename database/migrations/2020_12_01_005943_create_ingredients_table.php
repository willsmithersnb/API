<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration {

	public function up()
	{
		Schema::create('ingredients', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 150);
			$table->string('ingredient_type', 100)->index();
			$table->float('molecular_mass', 8,4);
			$table->float('osmolality', 8,4);
			$table->double('min_quantity', 23,15);
			$table->double('max_quantity', 23,15);
			$table->string('reference_num', 150)->nullable();
			$table->enum('reference_type', array('cas_no', 'cat_no'));
			$table->smallInteger('display_unit');
			$table->smallInteger('display_unit_type');
			$table->string('url', 150);
			$table->boolean('basal_enabled')->index()->default(1);
			$table->boolean('balanced_salt_enabled')->index()->default(1);
			$table->boolean('buffer_enabled')->index()->default(1);
			$table->boolean('cryo_enabled')->index()->default(1);
		});
	}

	public function down()
	{
		Schema::drop('ingredients');
	}
}