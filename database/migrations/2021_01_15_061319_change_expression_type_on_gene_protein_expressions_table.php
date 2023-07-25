<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeExpressionTypeOnGeneProteinExpressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gene_protein_expressions', function(Blueprint $table) {
            $table->dropForeign('gene_protein_expressions_expression_type_id_foreign');
            $table->dropColumn('expression_type_id');
        });
        Schema::table('gpe_recommendations', function(Blueprint $table) {
            $table->bigInteger('expression_type_id')->unsigned()->nullable();
            $table->foreign('expression_type_id')->references('id')->on('expression_types')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gpe_recommendations', function(Blueprint $table) {
            $table->dropForeign('gpe_recommendations_expression_type_id_foreign');
            $table->dropColumn('expression_type_id');
        });
        Schema::table('gene_protein_expressions', function (Blueprint $table) {
            $table->bigInteger('expression_type_id')->unsigned()->nullable();
            $table->foreign('expression_type_id')->references('id')->on('expression_types')
            ->onDelete('restrict')
            ->onUpdate('cascade');
        });
    }
}
