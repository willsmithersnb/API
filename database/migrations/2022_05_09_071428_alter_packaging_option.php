<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackagingOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packaging_options', function (Blueprint $table) {
            $table->bigInteger('min_lead_time')->default(16);
            $table->bigInteger('max_lead_time')->default(20);
            $table->bigInteger('min_cgmp_lead_time')->default(16);
            $table->bigInteger('max_cgmp_lead_time')->default(20);
            $table->bigInteger('fill_cost_per_litre')->default(0);
            $table->bigInteger('fill_price_per_litre')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packaging_option', function (Blueprint $table) {
            $table->dropColumn('min_lead_time');
            $table->dropColumn('max_lead_time');
            $table->dropColumn('min_cgmp_lead_time');
            $table->dropColumn('max_cgmp_lead_time');
            $table->dropColumn('fill_cost_per_litre');
            $table->dropColumn('fill_price_per_litre');
        });

    }
}
