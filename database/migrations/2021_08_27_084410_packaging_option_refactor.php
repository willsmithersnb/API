<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PackagingOptionRefactor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packaging_options', function (Blueprint $table) {
            $table->dropColumn('has_custom_value');
            $table->dropColumn('pricing_unit');
            $table->dropColumn('pricing_unit_type');
            $table->jsonb('configuration')->nullable();
            $table->bigInteger('fill_tolerance')->default(0);
            $table->smallInteger('fill_unit')->default(0);
            $table->smallInteger('unit_type')->default(0);
            $table->char('packaging_hash', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packaging_options', function (Blueprint $table) {
            $table->boolean('has_custom_value')->default(0);
            $table->smallInteger('pricing_unit')->default(0);
            $table->smallInteger('pricing_unit_type')->default(0);
            $table->dropColumn('configuration');
            $table->dropColumn('fill_tolerance');
            $table->dropColumn('fill_unit');
            $table->dropColumn('unit_type');
            $table->dropColumn('packaging_hash');
        });
    }
}
