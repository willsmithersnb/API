<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUploadedByToFirmware extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firmware', function (Blueprint $table) {
            $table->bigInteger('uploaded_by')->unsigned();
            $table->foreign('uploaded_by')->references('id')->on('users')
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
        Schema::table('firmware', function (Blueprint $table) {
            $table->dropForeign('firmware_uploaded_by_foreign');
            $table->dropColumn('uploaded_by');
        });
    }
}
