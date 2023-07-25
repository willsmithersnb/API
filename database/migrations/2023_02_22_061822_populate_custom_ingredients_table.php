<?php

use App\Models\CustomIngredient;
use App\Models\Item;
use App\Models\ItemList;
use App\Models\Quote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateCustomIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_ingredients', function (Blueprint $table) {
            // Commeted as not to be used 
            // $quotes = Quote::unScoped()->withTrashed()->get();
            // foreach ($quotes as $quote) {
            //     $item_list = ItemList::unScoped()->where('item_listable_id', $quote->id)
            //         ->where('item_listable_type', 'quote')->first();
            //     $item = Item::unScoped()->where('item_list_id', $item_list->id)->first();
            //     $custom_components = collect(json_decode($quote->custom_components));
            //     foreach($custom_components as $custom_component)
            //     {
            //         if(strlen(number_format($custom_component->quantity, 0, '', '')) > 18){
            //             $quantity = substr(number_format($custom_component->quantity, 0, '', ''), 0, 18);
            //         }else{
            //             $quantity = $custom_component->quantity;
            //         }
            //         $custom_ingredient = new CustomIngredient();
            //         $custom_ingredient->name = $custom_component->name;
            //         $custom_ingredient->cas_no = $custom_component->cas_no;
            //         $custom_ingredient->manufacturer = $custom_component->manufacturer;
            //         $custom_ingredient->catalog_no = ($custom_component->catalog_no ?? null);
            //         $custom_ingredient->quantity = $quantity;
            //         $custom_ingredient->quantity_unit = $custom_component->display_unit;
            //         $custom_ingredient->unit_type = $custom_component->unit_type;
            //         $custom_ingredient->customer_id = $quote->customer_id;
            //         $custom_ingredient->formula_id = $item->formula_id;
            //         $custom_ingredient->save();
            //     }
            // }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
