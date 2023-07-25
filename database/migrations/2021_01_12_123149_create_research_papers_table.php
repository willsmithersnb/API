<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchPapersTable extends Migration {

	public function up()
	{
		Schema::create('research_papers', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('title', 150);
			$table->bigInteger('journal_id')->unsigned();
			$table->text('link');
			$table->bigInteger('num_citations')->unsigned();
			$table->smallInteger('year')->unsigned();
			$table->string('author_name', 150);
			$table->bigInteger('cited_by');
			$table->smallInteger('years_out');
		});
	}

	public function down()
	{
		Schema::drop('research_papers');
	}
}
