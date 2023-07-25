<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_addons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name',150);
            $table->enum('conditional_variable',['num_liters', 'num_units', 'num_packaging_options']);
            $table->enum('pricing_type',['linear', 'variable']);
            $table->enum('cost_type',['linear', 'variable']);
            $table->boolean('is_customer_visible');
            $table->boolean('is_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_addons');
    }
}
