<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_coupons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('customer_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('coupon_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->bigInteger('discountable_id');
            $table->bigInteger('discountable_type');
            $table->foreignId('redeemed_by')->nullable()->constrained('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_coupons');
    }
}
