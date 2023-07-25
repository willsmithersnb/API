<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateDeliveryDateOnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(Order::withTrashed()->get() as $order)
        {
            $order->delivery_date = $order->created_at->addDays(91);
            $order->save();
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('delivery_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('delivery_date')->nullable(true)->change();
        });
    }
}
