<?php

use App\Models\Item;
use App\Models\ItemQcTestMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerIdToItemQcTestMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_qc_test_methods', function (Blueprint $table) {
            $table->bigInteger('customer_id')->unsigned()->nullable();
        });

        Schema::table('item_qc_test_methods', function (Blueprint $table) {
            foreach (ItemQcTestMethod::with('item.customer')->withTrashed()->get() as $itemQcTestMethod) {
                $itemQcTestMethod->customer_id = $itemQcTestMethod->item->customer->id;
                $itemQcTestMethod->save();
            }
            $table->bigInteger('customer_id')->nullable(false)->change();
            $table->foreign('customer_id')->references('id')->on('customers')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_qc_test_methods', function (Blueprint $table) {
            $table->dropForeign('item_qc_test_methods_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
    }
}
