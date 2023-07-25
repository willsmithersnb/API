<?php

use App\Models\FileUpload;
use Illuminate\Database\Migrations\Migration;

class DropExtensionFromFileUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(FileUpload::withTrashed()->get() as $file) {
            $file->file_name = $file->file_name . '.' . $file->extension;
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
            $file_details = explode('.', $file->file_name);
            array_pop($file_details);
            $file->file_name = join(".", $file_details);
            $file->save();
        }
    }
}