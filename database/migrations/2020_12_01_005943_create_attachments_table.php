<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration {

	public function up()
	{
		Schema::create('attachments', function(Blueprint $table) {
			$table->id();
			$table->morphs('attachable');
			$table->bigInteger('message_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
		});
	}

	public function down()
	{
		Schema::drop('attachments');
	}
}