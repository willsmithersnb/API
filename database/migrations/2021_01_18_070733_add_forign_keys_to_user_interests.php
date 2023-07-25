<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForignKeysToUserInterests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_interests', function (Blueprint $table) {
            $table->foreign('cell_media_id')->references('id')->on('cell_media')
            ->onDelete('restrict')
            ->onUpdate('cascade');
        });
        Schema::table('user_interests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::table('user_interests', function (Blueprint $table) {
            $table->dropForeign('user_interests_user_id_foreign');
        });
        Schema::table('user_interests', function (Blueprint $table) {
            $table->dropForeign('user_interests_cell_media_id_foreign');
        });
    }
}
