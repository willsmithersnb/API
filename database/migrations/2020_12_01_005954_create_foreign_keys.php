<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('grade_ingredients', function(Blueprint $table) {
			$table->foreign('grade_id')->references('id')->on('grades')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('grade_ingredients', function(Blueprint $table) {
			$table->foreign('ingredient_id')->references('id')->on('ingredients')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('formulas', function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('formulas', function(Blueprint $table) {
			$table->foreign('parent_id')->references('id')->on('formulas')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('pricing_logs', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('pricing_logs', function(Blueprint $table) {
			$table->foreign('grade_ingredient_id')->references('id')->on('grade_ingredients')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('users', function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('formula_ingredients', function(Blueprint $table) {
			$table->foreign('formula_id')->references('id')->on('formulas')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('formula_ingredients', function(Blueprint $table) {
			$table->foreign('grade_ingredient_id')->references('id')->on('grade_ingredients')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('products', function(Blueprint $table) {
			$table->foreign('formula_id')->references('id')->on('formulas')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('products', function(Blueprint $table) {
			$table->foreign('product_type_id')->references('id')->on('product_types')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('catalogs', function(Blueprint $table) {
			$table->foreign('product_id')->references('id')->on('products')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('messages', function(Blueprint $table) {
			$table->foreign('message_thread_id')->references('id')->on('message_threads')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('messages', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('message_threads', function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('addresses', function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('attachments', function(Blueprint $table) {
			$table->foreign('message_id')->references('id')->on('messages')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('items', function(Blueprint $table) {
			$table->foreign('item_list_id')->references('id')->on('item_lists')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('items', function(Blueprint $table) {
			$table->foreign('customization_id')->references('id')->on('formulas')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('items', function(Blueprint $table) {
			$table->foreign('item_summary_id')->references('id')->on('item_summaries')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('item_lists', function(Blueprint $table) {
			$table->foreign('coupon_id')->references('id')->on('coupons')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('item_qc_tests', function(Blueprint $table) {
			$table->foreign('item_id')->references('id')->on('items')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('item_qc_tests', function(Blueprint $table) {
			$table->foreign('qc_test_id')->references('id')->on('qc_tests')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('item_packaging_options', function(Blueprint $table) {
			$table->foreign('item_id')->references('id')->on('items')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('item_packaging_options', function(Blueprint $table) {
			$table->foreign('packaging_option_id')->references('id')->on('packaging_options')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('item_pricing_rules', function(Blueprint $table) {
			$table->foreign('pricing_rule_id')->references('id')->on('pricing_rules')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('item_pricing_rules', function(Blueprint $table) {
			$table->foreign('item_id')->references('id')->on('items')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('carts', function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('quotes', function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('configurable_pricing_logs', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('grade_ingredients', function(Blueprint $table) {
			$table->dropForeign('grade_ingredients_grade_id_foreign');
		});
		Schema::table('grade_ingredients', function(Blueprint $table) {
			$table->dropForeign('grade_ingredients_ingredient_id_foreign');
		});
		Schema::table('formulas', function(Blueprint $table) {
			$table->dropForeign('formulas_customer_id_foreign');
		});
		Schema::table('formulas', function(Blueprint $table) {
			$table->dropForeign('formulas_parent_id_foreign');
		});
		Schema::table('pricing_logs', function(Blueprint $table) {
			$table->dropForeign('pricing_logs_user_id_foreign');
		});
		Schema::table('pricing_logs', function(Blueprint $table) {
			$table->dropForeign('pricing_logs_grade_ingredient_id_foreign');
		});
		Schema::table('users', function(Blueprint $table) {
			$table->dropForeign('users_customer_id_foreign');
		});
		Schema::table('formula_ingredients', function(Blueprint $table) {
			$table->dropForeign('formula_ingredients_formula_id_foreign');
		});
		Schema::table('formula_ingredients', function(Blueprint $table) {
			$table->dropForeign('formula_ingredients_grade_ingredient_id_foreign');
		});
		Schema::table('products', function(Blueprint $table) {
			$table->dropForeign('products_formula_id_foreign');
		});
		Schema::table('products', function(Blueprint $table) {
			$table->dropForeign('products_product_type_id_foreign');
		});
		Schema::table('catalogs', function(Blueprint $table) {
			$table->dropForeign('catalogs_product_id_foreign');
		});
		Schema::table('messages', function(Blueprint $table) {
			$table->dropForeign('messages_message_thread_id_foreign');
		});
		Schema::table('messages', function(Blueprint $table) {
			$table->dropForeign('messages_user_id_foreign');
		});
		Schema::table('message_threads', function(Blueprint $table) {
			$table->dropForeign('message_threads_customer_id_foreign');
		});
		Schema::table('addresses', function(Blueprint $table) {
			$table->dropForeign('addresses_customer_id_foreign');
		});
		Schema::table('attachments', function(Blueprint $table) {
			$table->dropForeign('attachments_message_id_foreign');
		});
		Schema::table('items', function(Blueprint $table) {
			$table->dropForeign('items_item_list_id_foreign');
		});
		Schema::table('items', function(Blueprint $table) {
			$table->dropForeign('items_customization_id_foreign');
		});
		Schema::table('items', function(Blueprint $table) {
			$table->dropForeign('items_item_summary_id_foreign');
		});
		Schema::table('item_lists', function(Blueprint $table) {
			$table->dropForeign('item_lists_coupon_id_foreign');
		});
		Schema::table('item_qc_tests', function(Blueprint $table) {
			$table->dropForeign('item_qc_tests_item_id_foreign');
		});
		Schema::table('item_qc_tests', function(Blueprint $table) {
			$table->dropForeign('item_qc_tests_qc_test_id_foreign');
		});
		Schema::table('item_packaging_options', function(Blueprint $table) {
			$table->dropForeign('item_packaging_options_item_id_foreign');
		});
		Schema::table('item_packaging_options', function(Blueprint $table) {
			$table->dropForeign('item_packaging_options_packaging_option_id_foreign');
		});
		Schema::table('item_pricing_rules', function(Blueprint $table) {
			$table->dropForeign('item_pricing_rules_pricing_rule_id_foreign');
		});
		Schema::table('item_pricing_rules', function(Blueprint $table) {
			$table->dropForeign('item_pricing_rules_item_id_foreign');
		});
		Schema::table('carts', function(Blueprint $table) {
			$table->dropForeign('carts_customer_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_customer_id_foreign');
		});
		Schema::table('quotes', function(Blueprint $table) {
			$table->dropForeign('quotes_customer_id_foreign');
		});
		Schema::table('configurable_pricing_logs', function(Blueprint $table) {
			$table->dropForeign('configurable_pricing_logs_user_id_foreign');
		});
	}
}