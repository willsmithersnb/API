<?php

use App\Models\NBAI\Seat;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerIdToSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')
						->onDelete('restrict')
						->onUpdate('cascade');
        });

        $seats = Seat::with('user')->get();
        foreach($seats as $seat)
        {
            $seat->customer_id = $seat->user->customer_id;
            $seat->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropForeign('seats_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
    }
}
