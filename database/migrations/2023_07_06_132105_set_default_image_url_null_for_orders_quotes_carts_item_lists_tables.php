<?php

use App\Models\Cart;
use App\Models\ItemList;
use App\Models\Order;
use App\Models\Quote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetDefaultImageUrlNullForOrdersQuotesCartsItemListsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $orders = Order::unScoped()->get();
        foreach ($orders as $order) {
            $order->image_url = null;
            $order->save();
        }
        $quotes = Quote::unScoped()->get();
        foreach ($quotes as $quote) {
            $quote->image_url = null;
            $quote->save();
        }
        $carts = Cart::unScoped()->get();
        foreach($carts as $cart) {
            $cart->image_url = null;
            $cart->save();
        }
        $itemLists = ItemList::unScoped()->get();
        foreach($itemLists as $itemList) {
            $itemList->image_url = null;
            $itemList->save();
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
            $order->image_url = 'https://nb-lux-api-dev.s3.amazonaws.com/local/product/a5d59ecb-854d-413c-b1d4-6f1113cd19af__custom_media';
            $order->save();
        }
        $quotes = Quote::unScoped()->get();
        foreach ($quotes as $quote) {
            $quote->image_url = 'https://nb-lux-api-dev.s3.amazonaws.com/local/product/a5d59ecb-854d-413c-b1d4-6f1113cd19af__custom_media';
            $quote->save();
        }
        $carts = Cart::unScoped()->get();
        foreach($carts as $cart) {
            $cart->image_url = 'https://nb-lux-api-dev.s3.amazonaws.com/local/product/a5d59ecb-854d-413c-b1d4-6f1113cd19af__custom_media';
            $cart->save();
        }
        $itemLists = ItemList::unScoped()->get();
        foreach($itemLists as $itemList) {
            $itemList->image_url = 'https://nb-lux-api-dev.s3.amazonaws.com/local/product/a5d59ecb-854d-413c-b1d4-6f1113cd19af__custom_media';
            $itemList->save();
        }
    }
}
