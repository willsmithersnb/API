<?php

use App\Models\Address;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObjectHashToAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('object_hash')->nullable();
        });

        $addresses = Address::withOutGlobalScopes()->get();
        $addresses->makeHidden(['id', 'created_at']);
        foreach($addresses as $address) {
            $hash_array = $address->toArray();
            $address->object_hash = md5(implode('|', $hash_array));
            $address->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('object_hash');
        });
    }
}
