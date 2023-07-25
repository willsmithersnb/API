<?php

use App\Models\FileUploadLog;
use Illuminate\Database\Migrations\Migration;

class DropExtensionFromFileUploadLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(FileUploadLog::withTrashed()->get() as $file_log) {
            $file_log->file_name = $file_log->file_name . '.' . $file_log->extension;
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
            $file_details = explode('.', $file_log->file_name);
            array_pop($file_details);
            $file_log->file_name = join(".", $file_details);
            $file_log->save();
        }
    }
}
