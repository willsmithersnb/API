<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration {

	public function up()
	{
		Schema::create('coupons', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 100);
			$table->bigInteger('max_discount')->default(999999999999);
			$table->float('discount_percentage', 3,2)->default(0);
			$table->timestamp('expires_at')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('coupons');
	}
}