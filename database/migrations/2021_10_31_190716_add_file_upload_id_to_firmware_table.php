<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileUploadIdToFirmwareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firmware', function (Blueprint $table) {
            $table->bigInteger('file_upload_id')->unsigned();
            $table->foreign('file_upload_id')->references('id')->on('file_uploads')
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
            $table->dropForeign('firmware_file_upload_id_foreign');
            $table->dropColumn('file_upload_id');
        });
    }
}
