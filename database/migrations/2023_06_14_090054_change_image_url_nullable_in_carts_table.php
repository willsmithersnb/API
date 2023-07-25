<?php

use App\Models\Cart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImageUrlNullableInCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->text('image_url')->nullable()->change();
        });

        $carts = Cart::unScoped()->get();
        foreach ($carts as $cart) {
            $itemPackagingOptions = $cart->itemList()->unScoped()->first()->items()->unScoped()->first()->itemPackagingOptions()->unScoped()->get();
            foreach ($itemPackagingOptions as $itemPackagingOption) {
                $packagingType = $itemPackagingOption->packagingOption()->unScoped()->withTrashed()->first()->packaging_type;
                if ($packagingType == 'Pod') {
                    $cart->image_url = null;
                }
            }
            $cart->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $carts = Cart::unScoped()->get();
        foreach ($carts as $cart) {
            $cart->image_url = 'https://nb-lux-api-staging.s3.amazonaws.com/local/temp/product/a3c658e5-268a-4f67-b004-8ee7c2eccce6__nb-white';
            $cart->save();
        }
        Schema::table('carts', function (Blueprint $table) {
            $table->text('image_url')->nullable(false)->change();
        });
    }
}
