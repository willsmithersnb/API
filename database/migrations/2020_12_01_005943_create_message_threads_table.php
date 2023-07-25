<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageThreadsTable extends Migration {

	public function up()
	{
		Schema::create('message_threads', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('customer_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('message_threads');
	}
}