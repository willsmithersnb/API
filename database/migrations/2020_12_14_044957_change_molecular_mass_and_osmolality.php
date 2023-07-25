<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeMolecularMassAndOsmolality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->bigInteger('molecular_mass')->change();
            $table->bigInteger('osmolality')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE ingredients MODIFY molecular_mass DOUBLE(8, 4) DEFAULT 0');
        DB::statement('ALTER TABLE ingredients MODIFY osmolality DOUBLE(8, 4) DEFAULT 0');
    }
}
