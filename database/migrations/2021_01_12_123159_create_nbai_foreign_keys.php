<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNbaiForeignKeys extends Migration {

	public function up()
	{
		Schema::table('gene_protein_expressions', function(Blueprint $table) {
			$table->foreign('expression_type_id')->references('id')->on('expression_types')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('recommendations', function(Blueprint $table) {
			$table->foreign('cell_type_id')->references('id')->on('cell_types')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('recommendations', function(Blueprint $table) {
			$table->foreign('ingredient_id')->references('id')->on('ingredients')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('recommendations', function(Blueprint $table) {
			$table->foreign('research_paper_id')->references('id')->on('research_papers')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('research_papers', function(Blueprint $table) {
			$table->foreign('journal_id')->references('id')->on('journals')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('nb_recommendations', function(Blueprint $table) {
			$table->foreign('cell_type_id')->references('id')->on('cell_types')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('cqa_recommendations', function(Blueprint $table) {
			$table->foreign('recommendation_id')->references('id')->on('recommendations')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('cqa_recommendations', function(Blueprint $table) {
			$table->foreign('critical_quality_attribute_id')->references('id')->on('critical_quality_attributes')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('cell_media_recommendations', function(Blueprint $table) {
			$table->foreign('recommendation_id')->references('id')->on('recommendations')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('cell_media_recommendations', function(Blueprint $table) {
			$table->foreign('cell_media_id')->references('id')->on('cell_media')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('gpe_recommendations', function(Blueprint $table) {
			$table->foreign('recommendation_id')->references('id')->on('recommendations')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('gpe_recommendations', function(Blueprint $table) {
			$table->foreign('gene_protein_expression_id')->references('id')->on('gene_protein_expressions')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('gene_protein_expressions', function(Blueprint $table) {
			$table->dropForeign('gene_protein_expressions_expression_type_id_foreign');
		});
		Schema::table('recommendations', function(Blueprint $table) {
			$table->dropForeign('recommendations_cell_type_id_foreign');
		});
		Schema::table('recommendations', function(Blueprint $table) {
			$table->dropForeign('recommendations_ingredient_id_foreign');
		});
		Schema::table('recommendations', function(Blueprint $table) {
			$table->dropForeign('recommendations_research_paper_id_foreign');
		});
		Schema::table('research_papers', function(Blueprint $table) {
			$table->dropForeign('research_papers_journal_id_foreign');
		});
		Schema::table('nb_recommendations', function(Blueprint $table) {
			$table->dropForeign('nb_recommendations_cell_type_id_foreign');
		});
		Schema::table('cqa_recommendations', function(Blueprint $table) {
			$table->dropForeign('cqa_recommendations_recommendation_id_foreign');
		});
		Schema::table('cqa_recommendations', function(Blueprint $table) {
			$table->dropForeign('cqa_recommendations_critical_quality_attribute_id_foreign');
		});
		Schema::table('cell_media_recommendations', function(Blueprint $table) {
			$table->dropForeign('cell_media_recommendations_recommendation_id_foreign');
		});
		Schema::table('cell_media_recommendations', function(Blueprint $table) {
			$table->dropForeign('cell_media_recommendations_cell_media_id_foreign');
		});
		Schema::table('gpe_recommendations', function(Blueprint $table) {
			$table->dropForeign('gpe_recommendations_recommendation_id_foreign');
		});
		Schema::table('gpe_recommendations', function(Blueprint $table) {
			$table->dropForeign('gpe_recommendations_gene_protein_expression_id_foreign');
		});
	}
}