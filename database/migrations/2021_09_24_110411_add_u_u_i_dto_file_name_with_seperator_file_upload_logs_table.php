<?php

use App\Models\FileUploadLog;
use Illuminate\Database\Migrations\Migration;

class AddUUIDtoFileNameWithSeperatorFileUploadLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(FileUploadLog::withTrashed()->get() as $file_log) {
            $file_log->file_name = $file_log->uuid . '__' . $file_log->file_name;
            $file_log->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach(FileUploadLog::withTrashed()->get() as $file_log) {
            $file_log->file_name = explode('__', $file_log->file_name)[1];
            $file_log->save();
        }
    }
}
