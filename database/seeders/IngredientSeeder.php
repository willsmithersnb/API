<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\GradeIngredient;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Revolution\Google\Sheets\Facades\Sheets;
use Throwable;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mg_index = array_search('mg', config('enums.units')); 
        $mL_index = array_search('mL', config('enums.units'));
        $pg_index = array_search('pg', config('enums.units'));
        $pL_index = array_search('pL', config('enums.units'));
        $ng_index = array_search('ng', config('enums.units'));
        $nl_index = array_search('nL', config('enums.units'));

        $components_book = Sheets::spreadsheet(config('google.sheets.components_book_id'));
        $components_rows = $components_book->sheet('Components')->get();
        $components_header = $components_rows->pull(0);
        $components = Sheets::collection($components_header, $components_rows)->filter(function ($el) {
            return $el['index'] != '';
        });

        foreach ($components as $component) {
            Ingredient::updateOrCreate(['id' => $component['index']], [
                'id' => $component['index'],
                'name' => $component['componentname'],
                'prestashop_name' => $component['portal_name'],
                'ingredient_type' => $component['component_type'],
                'molecular_mass' => BigDecimal::of(floatval($component['molecular mass']))->multipliedBy(1000000000000)->dividedBy(1,0,RoundingMode::HALF_DOWN)->toInt(),
                'osmolality' => BigDecimal::of(floatval($component['osmolality']))->multipliedBy(1000000000)->dividedBy(1, 0, RoundingMode::HALF_DOWN)->toInt(),
                'min_quantity' => 0,
                'max_quantity' => 1000000000000000,
                'reference_num' => $component['cas_number'],
                'reference_type' => $this->get_reference_type($component['component_type']),
                'display_unit' => $component['unit'] == 'mL/L' ? $mL_index : $mg_index,
                'display_unit_type' => $component['unit'] == 'mL/L' ? 1 : 0,
                'url' => $component['url'],
                'basal_enabled' => $component['component_type'] != 'Buffers',
                'balanced_salt_enabled' => $component['component_type'] != 'Buffers',
                'buffer_enabled' => $component['component_type'] == 'Buffers',
                'cryo_enabled' => false
            ]);

            GradeIngredient::firstOrCreate([
                'grade_id' => 1,
                'ingredient_id' => $component['index'],
                'prestashop_id' => null,
                'price' => 0,
                'cost' => 0,
                'pricing_quantity' => 1,
                'pricing_unit' => 0,
                'is_active' => false,
            ]);
            GradeIngredient::firstOrCreate([
                'grade_id' => 2,
                'ingredient_id' => $component['index'],
                'prestashop_id' => null,
                'price' => 0,
                'cost' => 0,
                'pricing_quantity' => 1,
                'pricing_unit' => 0,
                'is_active' => false,
            ]);
        }



        $components_pricing_rows = $components_book->sheet('Component_Pricing')->get();
        $components_pricing_header = $components_pricing_rows->pull(0);
        $components_pricing = Sheets::collection($components_pricing_header, $components_pricing_rows)->filter(function ($el) {
            return $el['index'] != '';
        });

        foreach ($components_pricing as $component_pricing) {
            try{
                $magnitude = intval((floor(log10(abs(floatval(round($component_pricing['Unit Cost'], 9)))))+2)*-1);
                $price = BigDecimal::of(($component_pricing['Unit Price']))->multipliedBy(pow(10, $magnitude+2+6))->dividedBy(1, 0, RoundingMode::UP);
                $cost = BigDecimal::of(($component_pricing['Unit Cost']))->multipliedBy(pow(10, $magnitude+2+6))->dividedBy(1, 0, RoundingMode::UP);
                
                GradeIngredient::updateOrCreate(
                    [
                        'ingredient_id' => $component_pricing['Component ID'],
                        'grade_id' => $component_pricing['Grade ID']
                    ],
                    [
                        'prestashop_id' => $component_pricing['Prestashop ID'],
                        'price' => $price->toInt(),
                        'cost' => $cost->toInt(),
                        'pricing_quantity' => pow(10, $magnitude),
                        'pricing_unit' => $component_pricing['Unit'] == 'mL' ? $nl_index : $ng_index,
                        'is_active' => true,
                    ]
                );
            } catch(Throwable $th) {
                echo "[Error 500]: Seeder Failed on Line " . $th->getLine() . "\nWith message " . $th->getMessage() . "\n";
            }
            
        }
    }

    private function get_reference_type($component_type)
    {
        return collect([
            'Aminoacids & Dipeptides' => 'cas_no',
            'Nucleotides & Nucleosides' => 'cas_no',
            'Vitamins' => 'cas_no',
            'Inorganic Salts' => 'cas_no',
            'Proteins' => 'cat_no',
            'Cytokines and Growth Factors' => 'cat_no',
            'Carbohydrates' => 'cas_no',
            'Lipids' => 'cas_no',
            'Antibiotics' => 'cas_no',
            'Other Components' => 'cas_no',
            'Vitamins' => 'cas_no',
            'Buffers' => 'cas_no',
            '' => 'cas_no',
        ])->get($component_type);
    }
}
