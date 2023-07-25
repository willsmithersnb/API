<?php

use App\Models\FileUpload;
use Illuminate\Database\Migrations\Migration;

class AddUUIDtoFileNameWithSeperatorFileUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(FileUpload::withTrashed()->get() as $file) {
            $file->file_name = $file->uuid . '__' . $file->file_name;
            $file->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach(FileUpload::withTrashed()->get() as $file) {
            $file->file_name = explode('__', $file->file_name)[1];
            $file->save();
        }
    }
}
