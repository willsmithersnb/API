<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupForignKeysToRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->foreign('cqa_id')->references('id')->on('critical_quality_attributes')
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
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropForeign('recommendations_cqa_id_foreign');
        });
    }
}
