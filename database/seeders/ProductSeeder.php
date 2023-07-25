<?php

namespace Database\Seeders;

use App\Helper\UnitHelper;
use App\Models\Catalog;
use App\Models\Formula;
use App\Models\FormulaIngredient;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductType;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Revolution\Google\Sheets\Facades\Sheets;
use Throwable;

class ProductSeeder extends Seeder
{
    private const GOOGLE_URL_ID_REGEX = '/\/d\/(.*?)(\/|$)/';

    private $ml_index;
    private $mg_index;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->ml_index = array_search('mL', config('enums.units'));
        $this->mg_index = array_search('mg', config('enums.units'));
        $products_book = Sheets::spreadsheet(config('google.sheets.products_book_id'));
        $product_rows = $products_book->sheet('Product List')->get();
        $product_headers = $product_rows->pull(0);
        $products = Sheets::collection($product_headers, $product_rows)->filter(function ($product) {
            return $product['importable'];
        });

        // pre-fetching from database
        $db_products = Product::all();
        $db_formulas = Formula::whereIn('id', $db_products->pluck('formula_id'))->get();
        $db_ingredients = Ingredient::all();

        $success_count = 0;
        $number_of_products = sizeof($products);
        $this->command->getOutput()->progressStart($number_of_products);
        $run_time = time();
        foreach ($products as $product) {
            try {
                DB::beginTransaction();

                $db_product = $db_products->find($product['index']);

                if ($db_product) {
                    $formula = $db_formulas->find($db_product->formula_id);
                    if (!$formula)
                        throw new Exception('Formula not found');
                    $formula->update([
                        'name' => $product['name'],
                        'formula_hash' => 'DEMOHASH'
                    ]);
                } else {
                    $formula = Formula::create([
                        'name' => $product['name'],
                        'formula_hash' => 'DEMOHASH'
                    ]);
                }

                $product_type = ProductType::where('name', $product['product_type'])->firstOrFail();

                $saved_product = Product::updateOrCreate(['id' => $product['index']], [
                    'name' => $product['name'],
                    'product_type_id' => $product_type->id,
                    'supplier_name' => $product['supplier_name'],
                    'formula_id' => $formula->id,
                    'is_featured' => $product['featured'] === '1',
                    'is_displayed' => $product['displayed'] === '1'
                ]);

                $catalog_numbers_array = array_map('trim', explode(',', $product['catalog_numbers']));

                foreach ($catalog_numbers_array as $catalog_number) {
                    Catalog::updateOrCreate([
                        'number' => $catalog_number,
                        'product_id' => $saved_product->id
                    ]);
                }

                Catalog::whereNotIn('number', $catalog_numbers_array)
                    ->where('product_id', $saved_product->id)
                    ->delete();

                preg_match(ProductSeeder::GOOGLE_URL_ID_REGEX, $product['link_as_url'], $component_google_sheet_id);
                $components_book = Sheets::spreadsheet($component_google_sheet_id[1]);
                $components_book_sheet_list = $components_book->sheetList();
                $components_book_first_sheet_key = array_key_first($components_book_sheet_list);
                $components_book_first_sheet_name = $components_book_sheet_list[$components_book_first_sheet_key];
                $component_rows = $components_book->sheet($components_book_first_sheet_name)->get();
                $component_headers = $component_rows->pull(0);
                $components = Sheets::collection($component_headers, $component_rows)->filter(function ($component) {
                    return $component['index'] !== '';
                });

                // to deal with quota limits
                if($run_time+2 > time()){
                    sleep(1.5);
                }
                $run_time = time();

                $modified_ingredient_ids = [];
                foreach ($components as $component) {
                    $ingredient = $db_ingredients->find($component['index']);
                    if (!$ingredient){
                        throw new Exception('Ingredient not found');
                    }

                    // in some sheets, the column name is 'unit' in others it is 'units'
                    if (!empty($component['unit'])){
                        $unit = explode('/', $component['unit'])[0];
                    }else if (!empty($component['units'])){
                        $unit = explode('/', $component['units'])[0];
                    }else{
                        throw new Exception('Unit does not exist for FormulaIngredient');
                    }

                    if ($unit === 'mg') {
                        $unit_type = 0;
                        $quantity_unit = $this->mg_index;
                        $quantity_unit_as_string = 'pg';
                    } else if ($unit === 'mL') {
                        $unit_type = 1;
                        $quantity_unit = $this->ml_index;
                        $quantity_unit_as_string = 'pL';
                    } else{
                        throw new Exception('Unhandled unit_type for FormulaIngredient');
                    }

                    // to deal with number string with commas
                    $component_value = floatval(str_replace(",", "", $component['value']));
                    $quantity = (UnitHelper::convertTo($component_value, $unit, $quantity_unit_as_string))->toBigInteger();

                    FormulaIngredient::updateOrCreate([
                        'ingredient_id' => $ingredient->id,
                        'formula_id' => $formula->id,
                        'quantity' => $quantity,
                        'quantity_unit' => $quantity_unit,
                        'pricing_unit' => $ingredient->pricing_unit,
                        'unit_type' => $unit_type,
                        'price' => 0,
                        'cost' => 0,
                    ]);
                    array_push($modified_ingredient_ids, $ingredient->id);
                }

                FormulaIngredient::whereNotIn('ingredient_id', $modified_ingredient_ids)
                    ->where('formula_id', $formula->id)
                    ->delete();

                DB::commit();
                $success_count++;
            } catch (Throwable $th) {
                DB::rollBack();
                echo "\n[Error 500]: Product Seeder Failed on Line {$th->getLine()} for Product: {$product["name"]}.\nWith message {$th->getMessage()}\n";
            }
            $this->command->getOutput()->progressAdvance();
        }

        // sequence value fix for products table
        DB::select('SELECT setval(\'products_id_seq\', (SELECT MAX(id) from "products"))');

        $this->command->getOutput()->progressFinish();
        echo "\nSuccessfully imported $success_count/$number_of_products Products\n";
    }
}
