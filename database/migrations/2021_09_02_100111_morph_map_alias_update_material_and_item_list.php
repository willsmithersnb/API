<?php

use App\Models\ItemList;
use App\Models\Material;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MorphMapAliasUpdateMaterialAndItemList extends Migration
{
    protected $materialMorphMap = [
        'packaging_option' => 'App\Models\PackagingOption',
        'ingredient' => 'App\Models\Ingredient'
    ];


    protected $itemListMorphMap = [
        'cart' => 'App\Models\Cart',
        'order' => 'App\Models\Order',
        'quote' => 'App\Models\Quote',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            foreach ($this->materialMorphMap as $materialable_type => $materialable_type_class) {
                Material::where('materialable_type', $materialable_type_class)->update(array('materialable_type' => $materialable_type));
            }
        });

        Schema::table('item_lists', function (Blueprint $table) {
            foreach ($this->itemListMorphMap as $item_listable_type => $item_listable_type_class) {
                ItemList::where('item_listable_type', $item_listable_type_class)->update(array('item_listable_type' => $item_listable_type));
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            foreach ($this->materialMorphMap as $materialable_type => $materialable_type_class) {
                Material::where('materialable_type', $materialable_type)->update(array('materialable_type' => $materialable_type_class));
            }
        });

        Schema::table('item_lists', function (Blueprint $table) {
            foreach ($this->itemListMorphMap as $item_listable_type => $item_listable_type_class) {
                ItemList::where('item_listable_type', $item_listable_type)->update(array('item_listable_type' => $item_listable_type_class));
            }
        });
    }
}
