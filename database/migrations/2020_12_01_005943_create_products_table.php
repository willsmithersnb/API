<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {

	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('formula_id')->unsigned()->nullable();
			$table->bigInteger('product_type_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 150);
			$table->string('supplier_name', 150);
			$table->boolean('is_featured');
			$table->boolean('is_displayed');
			$table->smallInteger('lead_time')->default('7');
		});
	}

	public function down()
	{
		Schema::drop('products');
	}
}