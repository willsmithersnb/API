<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyPairsToProductDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_documents', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('restrict')
                ->onUpdate('cascade');
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
        Schema::table('product_documents', function (Blueprint $table) {
            $table->dropForeign('product_documents_product_id_foreign');
            $table->dropForeign('product_documents_file_upload_id_foreign');
        });
    }
}
