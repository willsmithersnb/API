<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSummariesTable extends Migration {

	public function up()
	{
		Schema::create('item_summaries', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->enum('format', array('Liquid', 'Powder'))->nullable();
			$table->string('concentration', 4)->nullable();
			$table->string('pH', 12)->nullable();
			$table->boolean('cgmp_manufacturing')->default(0);
			$table->double('formulation_weight', 12,4)->nullable();
			$table->double('predicted_osmolality', 12,4)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('item_summaries');
	}
}