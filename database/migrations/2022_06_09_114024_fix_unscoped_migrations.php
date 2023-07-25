<?php

use App\Models\Message;
use App\Models\MessageThread;
use App\Models\NBAI\Seat;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixUnscopedMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $message_threads = MessageThread::unScoped()->withTrashed()->get();
        $messages = Message::unScoped()->withTrashed()->get();
        foreach($messages as $message)
        {
            $message_thread = $message_threads->firstOrFail('id', $message->message_thread_id);
            $message->customer_id = $message_thread->customer_id;
            $message->save();
        }

        $seats = Seat::unScoped()->withTrashed()->get();
        $user = User::unScoped()->withTrashed()->get();
        foreach($seats as $seat)
        {
            $seat->customer_id = $user->firstOrFail('id', $seat->user_id)->customer_id;
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
        $messages = Message::unScoped()->withTrashed()->update(['customer_id'=>null]);
        $seat = Seat::unScoped()->withTrashed()->update(['customer_id'=>null]);
    }
}
