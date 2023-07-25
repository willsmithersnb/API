<?php

use App\Models\ItemList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerIdToItemListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_lists', function (Blueprint $table) {
            $table->bigInteger('customer_id')->unsigned()->default(1);
            $table->foreign('customer_id')->references('id')->on('customers')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        foreach (ItemList::all() as $item_list_obj) {
            if (!is_null($item_list_obj->item_listable->customer_id)) {
                $item_list_obj->customer_id = $item_list_obj->item_listable->customer_id;
                $item_list_obj->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_lists', function (Blueprint $table) {
            $table->dropForeign('item_lists_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
    }
}
