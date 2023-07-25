<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnabledAttributesToPackagingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packaging_options', function (Blueprint $table) {
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
        Schema::table('packaging_options', function (Blueprint $table) {
            $table->dropColumn('basal_enabled');
            $table->dropColumn('balanced_salt_enabled');
            $table->dropColumn('buffer_enabled');
            $table->dropColumn('cryo_enabled');
        });
    }
}
