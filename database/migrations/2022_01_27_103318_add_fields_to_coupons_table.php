<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->bigInteger('min_amount')->nullable();
            $table->string('coupon_type',150)->default('nb_lux');
            $table->string('coupon_code',150)->unique();
            $table->timestamp('valid_from')->default('now()');
            $table->integer('max_redemptions')->default(9999);
            $table->string('limit_redemption_by',100)->default('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('min_amount');
            $table->dropColumn('coupon_type');
            $table->dropColumn('coupon_code');
            $table->dropColumn('valid_from');
            $table->dropColumn('max_redemptions');
            $table->dropColumn('limit_redemption_by');
        });
    }
}
