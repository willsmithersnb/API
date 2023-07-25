<?php

use App\Models\Cart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->unsigned();
        });

        Schema::table('carts', function (Blueprint $table) {
            foreach (Cart::with('customer.users')->withTrashed()->get() as $cart) {
                $cart->user_id = $cart->customer->users[0]->id;
                $cart->save();
            }
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->bigInteger('user_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('carts_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
