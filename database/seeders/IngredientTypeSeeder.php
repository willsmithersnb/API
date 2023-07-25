<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\IngredientType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredient_types = array_unique(Ingredient::all()->makeVisible('ingredient_type')->pluck('ingredient_type')->toArray());
        $existing_ingredient_types = IngredientType::all()->pluck('name')->toArray();
        $new_ingredient_types = array_diff($ingredient_types, $existing_ingredient_types);
        foreach ($new_ingredient_types as $ingredientType)
        {
            IngredientType::create(['name' => $ingredientType]);
        }

        foreach(IngredientType::all() as $ingredient_type)
        {
            Ingredient::where('ingredient_type', $ingredient_type->name)->update(['ingredient_type_id' => $ingredient_type->id]);
        }
    }
}
