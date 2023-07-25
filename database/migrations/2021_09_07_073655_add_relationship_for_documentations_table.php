<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipForDocumentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentations', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')
                ->onDelete('restrict')
                ->onUpdate('cascade'); 

            $table->foreign('file_upload_id')->references('id')->on('file_uploads')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');

            $table->foreign('customer_id')->references('id')->on('customers')
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
        Schema::table('documentations', function (Blueprint $table) {
            $table->dropForeign('documentations_order_id_foreign');
            $table->dropForeign('documentations_file_upload_id_foreign');
            $table->dropForeign('documentations_customer_id_foreign');
        });

    }
}
