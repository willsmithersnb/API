<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeScoreColumnToDecimalOnRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('database.default') == 'pgsql') {
            DB::statement('ALTER TABLE recommendations ALTER COLUMN score TYPE NUMERIC(6,3) USING (trim(score)::numeric(6,3))');
        }else{
            Schema::table('recommendations', function (Blueprint $table) {
                $table->decimal('score',6,3)->default(0)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->string('score', 150)->change();
        });
    }
}
