<?php

use App\Models\ItemList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImageUrlNullableInItemListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_lists', function (Blueprint $table) {
            $table->text('image_url')->nullable()->change();
        });

        $itemLists = ItemList::unScoped()->get();
        foreach ($itemLists as $itemList) {
            $itemPackagingOptions = $itemList->items()->unScoped()->first()->itemPackagingOptions()->unScoped()->get();
            foreach ($itemPackagingOptions as $itemPackagingOption) {
                $packagingOption = $itemPackagingOption->packagingOption()->unScoped()->withTrashed()->first();
                if ($packagingOption->packaging_type === 'Pod') {
                    $itemList->image_url = null;
                }
            }
            $itemList->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $itemLists = ItemList::unScoped()->get();
        foreach ($itemLists as $itemList) {
            $itemList->image_url = 'https://nb-lux-api-staging.s3.amazonaws.com/local/temp/product/a3c658e5-268a-4f67-b004-8ee7c2eccce6__nb-white';
            $itemList->save();
        }
        Schema::table('item_lists', function (Blueprint $table) {
            $table->text('image_url')->nullable(false)->change();
        });
    }
}
