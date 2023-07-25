<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration {

	public function up()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->dropColumn('name');
			$table->bigInteger('customer_id')->unsigned()->nullable();
			$table->softDeletes();
			$table->string('first_name', 100);
			$table->string('last_name', 100);
			$table->string('department', 150);
			$table->string('field_of_work', 191);
			$table->string('job_title', 191);
			$table->jsonb('cell_type_interests');
			$table->string('profile_picture', 191);
			$table->boolean('has_accepted_terms')->default(0);
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->string('name');
			$table->dropColumn('first_name');
			$table->dropColumn('last_name');
			$table->dropColumn('department');
			$table->dropColumn('field_of_work');
			$table->dropColumn('job_title');
			$table->dropColumn('cell_type_interests');
			$table->dropColumn('profile_picture');
			$table->dropColumn('has_accepted_terms');
		});
	}
}