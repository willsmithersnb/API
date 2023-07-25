<?php

use App\Models\PackagingOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObjectHashToPackagingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packaging_options', function (Blueprint $table) {
            $table->string('object_hash')->nullable();
        });

        $packagingOptions = PackagingOption::withOutGlobalScopes()->get();
        $packagingOptions->makeHidden(['id', 'created_at']);
        foreach($packagingOptions as $packagingOption) {
            $hash_array = $packagingOption->toArray();
            $packagingOption->object_hash = md5(implode('|', $hash_array));
            $packagingOption->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packaging_options', function (Blueprint $table) {
            $table->dropColumn('object_hash');
        });
    }
}
