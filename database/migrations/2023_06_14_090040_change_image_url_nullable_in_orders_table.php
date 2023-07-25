<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImageUpload;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImageUrlNullableInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('image_url')->nullable()->change();
        });

        $orders = Order::unScoped()->get();
        foreach ($orders as $order) {
            $itemPackagingOptions = $order->itemList()->unScoped()->first()->items()->unScoped()->first()->itemPackagingOptions()->unScoped()->get();
            foreach ($itemPackagingOptions as $itemPackagingOption) {
                $packagingType = $itemPackagingOption->packagingOption()->unScoped()->withTrashed()->first()->packaging_type;
                if ($packagingType == 'Pod') {
                    $order->image_url = null;
                }
            }
            $order->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $orders = Order::unScoped()->get();
        foreach ($orders as $order) {
            $order->image_url = 'https://nb-lux-api-staging.s3.amazonaws.com/local/temp/product/a3c658e5-268a-4f67-b004-8ee7c2eccce6__nb-white';
            $order->save();
        }
        Schema::table('orders', function (Blueprint $table) {
            $table->text('image_url')->nullable(false)->change();
        });
    }
}
