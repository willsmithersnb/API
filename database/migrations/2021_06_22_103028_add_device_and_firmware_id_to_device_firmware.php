<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceAndFirmwareIdToDeviceFirmware extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_firmware', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('firmware_id')->references('id')->on('firmware')
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
        Schema::table('device_firmware', function (Blueprint $table) {
            $table->dropForeign('device_firmware_device_id_foreign');
            $table->dropForeign('device_firmware_firmware_id_foreign');
        });
    }
}
