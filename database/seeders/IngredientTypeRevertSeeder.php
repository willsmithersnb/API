<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\IngredientType;
use Illuminate\Database\Seeder;

class IngredientTypeRevertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(IngredientType::all() as $ingredient_type)
        {
            Ingredient::where('ingredient_type_id', $ingredient_type->id)->update(['ingredient_type' => $ingredient_type->name]);
        }
    }
}
