<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyConstraintToFileable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fileables', function (Blueprint $table) {
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
        Schema::table('fileables', function (Blueprint $table) {
            $table->dropForeign('fileables_file_upload_id_foreign');
        });
    }
}
