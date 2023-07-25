<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApplyPerVariableToPricingAddonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_addons', function (Blueprint $table) {
            $table->enum('apply_per', ['order', 'total_order_size', 'total_num_units'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_addons', function (Blueprint $table) {
            $table->dropColumn('apply_per');
        });
    }
}
