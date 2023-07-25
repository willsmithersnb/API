<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFieldsInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_status');
            $table->dropColumn('payment_status');
            $table->dropColumn('payment_type');
            $table->smallInteger('order_status_id')->default(0);
            $table->smallInteger('payment_status_id')->default(0);
            $table->smallInteger('payment_type_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_status', array('order_submitted', 'order_received', 'specification_in_review', 'awaiting_customer_specification_approval', 'pre_production', 'in_production', 'qc_testing', 'preparing_for_shipment', 'shipped', 'delivered'))->default('order_submitted');
            $table->enum('payment_status', array('paid', 'unpaid', 'partial_payment'))->default('unpaid');
            $table->enum('payment_type', array('purchase_order', 'cash', 'bank_deposit', 'credit_card'))->default('purchase_order');
        });
    }
}
