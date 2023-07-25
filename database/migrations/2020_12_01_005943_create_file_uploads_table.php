<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadsTable extends Migration {

	public function up()
	{
		Schema::create('file_uploads', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('bucket_path', 191);
			$table->uuid('uuid');
			$table->string('extension', 100);
		});
	}

	public function down()
	{
		Schema::drop('file_uploads');
	}
}