<?php

use App\Models\Quote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->unsigned();
        });

        Schema::table('quotes', function (Blueprint $table) {
            foreach (Quote::with('customer.users')->withTrashed()->get() as $quote) {
                $quote->user_id = $quote->customer->users[0]->id;
                $quote->save();
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
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign('quotes_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
