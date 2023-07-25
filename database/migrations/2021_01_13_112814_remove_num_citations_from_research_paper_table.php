<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNumCitationsFromResearchPaperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('research_papers', function (Blueprint $table) {
            $table->dropColumn('num_citations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('research_papers', function (Blueprint $table) {
            $table->bigInteger('num_citations')->unsigned();
        });
    }
}
