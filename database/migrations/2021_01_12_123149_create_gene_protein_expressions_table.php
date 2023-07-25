<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneProteinExpressionsTable extends Migration {

	public function up()
	{
		Schema::create('gene_protein_expressions', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 150);
			$table->bigInteger('expression_type_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('gene_protein_expressions');
	}
}