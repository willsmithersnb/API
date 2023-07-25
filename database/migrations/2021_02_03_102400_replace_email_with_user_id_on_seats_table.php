<?php

use App\Models\NBAI\Seat;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReplaceEmailWithUserIdOnSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function() {
            Schema::table('seats', function ($table) {
                $table->bigInteger('user_id')->unsigned()->nullable();
            });

            $seats = Seat::join('users', 'users.email', '=', 'seats.email')->select('users.id AS user_user_id', 'seats.*')->get();

            foreach ($seats as $seat) {
                $seat->update(['user_id' => $seat->user_user_id]);
            }

            Schema::table('seats', function ($table) {
                $table->dropColumn('email');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function() {
            Schema::table('seats', function ($table) {
                $table->string('email')->nullable()->unique();
            });

            $seats = Seat::join('users', 'users.id', '=', 'seats.user_id')->select('users.email AS user_email', 'seats.*')->get();

            foreach ($seats as $seat) {
                $seat->email = $seat->user_email;
                $seat->save();
            }

            Schema::table('seats', function ($table) {
                $table->dropColumn('user_id');
                $table->string('email')->nullable(false)->change();
            });
        });
    }
}
