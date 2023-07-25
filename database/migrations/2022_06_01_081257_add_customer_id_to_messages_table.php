<?php

use App\Models\MessageThread;
use App\Models\Message;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCustomerIdToMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')
						->onDelete('restrict')
						->onUpdate('cascade');
        });

        $message_threads = MessageThread::all();
        $messages = Message::all();
        foreach($messages as $message)
        {
            $message_thread = $message_threads->firstOrFail('id', $message->message_thread_id);
            $message->customer_id = $message_thread->customer_id;
            $message->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
    }
}
