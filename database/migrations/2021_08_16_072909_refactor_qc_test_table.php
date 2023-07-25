<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorQcTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_tests', function (Blueprint $table) {
            $table->string('ui_component_name');
            $table->string('description')->nullable();
            $table->boolean('has_custom_value')->default(0);
            $table->boolean('basal_enabled')->index()->default(1);
            $table->boolean('balanced_salt_enabled')->index()->default(1);
            $table->boolean('buffer_enabled')->index()->default(1);
            $table->boolean('cryo_enabled')->index()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qc_tests', function (Blueprint $table) {
            $table->dropColumn('ui_component_name');
            $table->dropColumn('description');
            $table->dropColumn('has_custom_value');
            $table->dropColumn('basal_enabled');
            $table->dropColumn('balanced_salt_enabled');
            $table->dropColumn('buffer_enabled');
            $table->dropColumn('cryo_enabled');
        });
    }
}
