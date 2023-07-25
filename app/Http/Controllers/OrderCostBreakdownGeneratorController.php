<?php

namespace App\Http\Controllers;

use App\Helper\UnitHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\IngredientType;
use App\Models\Order;
use App\Models\PackagingOption;
use App\Models\PricingAddon;
use App\Models\PricingAddonTier;
use App\Models\QcTest;
use App\Models\QcTestMethod;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OrderCostBreakdownGeneratorController extends Controller
{
    protected $total_order_size;
    protected $ingredients;
    protected $ingredient_types;
    protected $packaging_options;
    protected $ingredients_total_price_row;
    protected $packaging_options_total_price_row;
    protected $pricing_addon_total_price_row;
    protected $testing_options_total_price_row;
    protected $packaging_quantity_rows;

    protected $row_count = 5;
    protected $summation_start_row = 5;
    protected $total = 0;

    // setting up styles
    protected $pricing_headers_styles = [
        'font' => [
            'bold' => true,
            'size' => 12,
            'name' => 'Arial'
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        ],
    ];

    protected $ingredient_types_styles = [
        'font' => [
            'bold' => true,
            'size' => 10,
            'name' => 'Arial'
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'd2e0f0'
            ],
        ]
    ];

    protected $total_order_size_styles = [
        'font' => [
            'bold' => true,
            'size' => 10,
            'name' => 'Arial'
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'efefef'
            ]
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ];

    protected $column_styles = [
        'font' => [
            'bold' => false,
            'size' => 10,
            'name' => 'Arial'
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'efefef'
            ]
        ]
    ];

    protected $border_styles = [
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ]
        ]
    ];

    protected $font_styles = [
        'font' => [
            'bold' => false,
            'size' => 10,
            'name' => 'Arial'
        ]
    ];

    protected $total_values_text_styles = [
        'font' => [
            'bold' => true,
            'size' => 10,
            'name' => 'Arial'
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ]
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        ],
    ];

    protected $total_price_styles = [
        'font' => [
            'bold' => false,
            'size' => 10,
            'name' => 'Arial'
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ]
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'd9e6d3'
            ]
        ]
    ];

    protected $discount_price_styles = [
        'font' => [
            'bold' => false,
            'size' => 10,
            'name' => 'Arial'
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ]
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'FFFEC4'
            ]
        ]
    ];

    public function __construct()
    {
        $this->ingredients  = Ingredient::all();
        $this->ingredient_types = IngredientType::all();
    }

    public function calculateTotalOrderSize($packaging_options)
    {
        foreach ($packaging_options as $packaging_option) {
            $this->total_order_size += $packaging_option->quantity * $packaging_option->fill_amount;
        }
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, int $order_id)
    {
        // file name.
        $template_name = 'order_' . $order_id . '_cost_breakdown';

        // fetching data
        $item_list = Order::unScoped()->findOrFail($order_id)->itemList()->unScoped()->first();
        $items = $item_list->items()->unScoped()->get();
        $formula_ingredients = $items[0]->formula()->unScoped()->first()->formulaIngredients()->unScoped()->get();
        $item_packaging_options = $items[0]->itemPackagingOptions()->unScoped()->get();
        $item_qc_testing_methods = $items[0]->itemQcTestMethod()->unScoped()->get();
        $item_pricing_addon_tiers = $items[0]->itemPricingAddonTiers()->get();
        $this->calculateTotalOrderSize($item_packaging_options);

        $testing_option_count = count($item_qc_testing_methods);
        $addon_option_count = count($item_pricing_addon_tiers);

        // creating a sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('Order Price Breakdown');

        // adding currency format to C and E columns
        $spreadsheet->getActiveSheet()->getStyle('C')->getNumberFormat()
            ->setFormatCode('$0.00000000000');

        $spreadsheet->getActiveSheet()->getStyle('D')->getNumberFormat()
            ->setFormatCode('$0.00000000000');

        $spreadsheet->getActiveSheet()->getStyle('E')->getNumberFormat()
            ->setFormatCode('0.00000000000');

        $spreadsheet->getActiveSheet()->getStyle('F')->getNumberFormat()
            ->setFormatCode('$0.00000000000');

        $spreadsheet->getActiveSheet()->getStyle('G')->getNumberFormat()
            ->setFormatCode('$0.00000000000');

        $spreadsheet->getActiveSheet()->getStyle('I')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        // fill cell with data
        $sheet->getStyle('I2:J2')->applyFromArray($this->total_order_size_styles);
        $sheet->setCellValue('I2', 'Total Order Size');
        $sheet->setCellValue('J2', UnitHelper::convertTo($this->total_order_size, 'pL', 'L'));

        // printing ingredients and ingredient types to sheet
        $this->writeIngredientsToSheet($sheet, $formula_ingredients);

        // printing packaging options to sheet
        $this->writePackagingOptionsToSheet($sheet, $item_packaging_options);

        // printing testing options to sheet
        if ($testing_option_count != null) {
            $this->writeTestingOptionsToSheet($sheet, $item_qc_testing_methods);
        }

        // printing pricing addons to sheet
        if ($addon_option_count != null) {
            $this->writeAddonPricingToSheet($sheet, $item_pricing_addon_tiers, $item_packaging_options);
        }

        // adding extra space by increasing the row count
        $this->row_count += 3;
        if ($item_list->discount != null) {
            $this->row_count += 2;
            $sheet->mergeCells('A' . $this->row_count . ':G' . $this->row_count);
            $sheet->getStyle('A' . $this->row_count . ':G' . $this->row_count)->applyFromArray($this->total_values_text_styles);
            $sheet->setCellValue('A' . $this->row_count, 'Discounts');
            $sheet->getStyle('H' . $this->row_count)->applyFromArray($this->discount_price_styles);
            $sheet->setCellValue('H' . $this->row_count, $item_list->discount / 100);
        }

        $this->row_count += 2;

        // sum of all the total pricing cell values and adding styles
        $sheet->mergeCells('A' . $this->row_count . ':G' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':G' . $this->row_count)->applyFromArray($this->total_values_text_styles);
        $sheet->setCellValue('A' . $this->row_count, 'Total Price (Final Ingredients Price + Packaging Price + Testing Price + Add-on Price)');
        $sheet->getStyle('H' . $this->row_count)->applyFromArray($this->total_price_styles);
        $total_cols = [];

        if (!empty($this->ingredients_total_price_row)) {
            array_push($total_cols, 'I' . $this->ingredients_total_price_row);
        }
        if (!empty($this->packaging_options_total_price_row)) {
            array_push($total_cols, 'I' . $this->packaging_options_total_price_row);
        }
        if (!empty($this->testing_options_total_price_row)) {
            array_push($total_cols, 'I' . $this->testing_options_total_price_row);
        }
        if (!empty($this->pricing_addon_total_price_row)) {
            array_push($total_cols, 'I' . $this->pricing_addon_total_price_row);
        }

        $sheet->setCellValue('H' . $this->row_count, '=SUM(' . implode(',', $total_cols) . ') - (H' . ($this->row_count - 2) .')');

        // setting the dimensions of the columns auto size
        for ($i = 1; $i < $this->row_count; $i++) {
            $column = $this->getExcelColumn($i);
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // adding currency format to the Total Price cell
        $spreadsheet->getActiveSheet()->getStyle('J3:J' . $this->row_count)->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

        $spreadsheet->getActiveSheet()->getStyle('H3:H' . $this->row_count)->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        // printing pricing addon list sheet
        $this->createAddonSheet($spreadsheet);

        // downloading .xlsx file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/ms-excel');
        header('Content-Disposition: attachment; filename=' . $template_name . '.xlsx');
        $writer->save('php://output');
    }

    public function writeIngredientsToSheet($sheet, $formula_ingredients)
    {
        $ingredient_ids = $formula_ingredients->pluck('ingredient_id');
        $ingredients = $this->ingredients->whereIn('id', $ingredient_ids);
        $ingredient_type_ids = $ingredients->pluck('ingredient_type_id');
        $ingredient_types = $this->ingredient_types->whereIn('id', $ingredient_type_ids)->sortBy('name');

        $powder_ingredients = [];
        $liquid_ingredients = [];

        //filtering the ingredients using the ingredient type and the unit type
        foreach ($ingredient_types as $ingredient_type) {
            $filtered_ingredients_powder = $ingredients->where('ingredient_type_id', $ingredient_type->id)->where('unit_type', 0);
            $filtered_ingredients_liquid = $ingredients->where('ingredient_type_id', $ingredient_type->id)->where('unit_type', 1);

            if (count($filtered_ingredients_powder) > 0) {
                $powder_ingredients[$ingredient_type->name] = $filtered_ingredients_powder;
            }

            if (count($filtered_ingredients_liquid) > 0) {
                $liquid_ingredients[$ingredient_type->name . ' (Liquid)'] = $filtered_ingredients_liquid;
            }
        }

        $sheet->mergeCells('A3:F3');
        $sheet->getStyle('A3:F3')->applyFromArray($this->pricing_headers_styles);
        $sheet->setCellValue('A3', 'Ingredients Pricing');

        // filtering ingredients accordingly and printing to sheet
        $this->row_count--;
        $this->writeIngredientListToSheet($sheet, $powder_ingredients, $formula_ingredients);

        if (count($liquid_ingredients) > 0) {
            $this->writeIngredientListToSheet($sheet, $liquid_ingredients, $formula_ingredients, true);
        }

        // applying border outline for the ingredients
        $break = $this->row_count - 1;
        $sheet->getStyle('A4:J' . $break)->applyFromArray($this->border_styles);

        // sum of the ingredient cell values and applying styles for the total pricing cells
        $summation_end_row = $this->row_count - 1;
        $sheet->mergeCells('A' . $this->row_count . ':G' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':G' . $this->row_count)->applyFromArray($this->total_values_text_styles);
        $sheet->setCellValue('A' . $this->row_count, 'Final Ingredients Price (Total Ingredients Price * Total Order Size)');
        $sheet->setCellValue('H' . $this->row_count, '=SUM(H5:H' . $summation_end_row . ')');
        $sheet->setCellValue('I' . $this->row_count, '=SUM(I5:I' . $summation_end_row . ')');
        $sheet->setCellValue('J' . $this->row_count, '=((I' . $this->row_count . '-H' . $this->row_count . ')/I' . $this->row_count . ')');
        $sheet->getStyle('H' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->total_price_styles);
        $this->ingredients_total_price_row = $this->row_count;
        $this->row_count += 2;
    }

    public function writePackagingOptionsToSheet($sheet, $item_packaging_options)
    {
        // fill packaging options and styling
        $sheet->mergeCells('A' . $this->row_count . ':J' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->pricing_headers_styles);
        $sheet->setCellValue('A' . $this->row_count, 'Packaging Pricing');
        $this->row_count++;
        $packaging_columns = array('Default Package Type', 'Default Package Size (L)', 'Unit Cost', 'Unit Price', 'Default Packaging Quantity', '', '', 'Total Packaging Cost', 'Total Packaging Price', 'Final Margin');
        $sheet->fromArray($packaging_columns, NULL, 'A' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->column_styles);
        $this->row_count++;
        $break = $this->row_count - 1;
        $summation_start_row = $this->row_count;

        // setting cell values of packaging options and styling
        foreach ($item_packaging_options as $item_packaging_option) {
            $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->font_styles);
            $packaging_option = PackagingOption::unScoped()->withTrashed()->get()->firstOrFail('id', $item_packaging_option->packaging_option_id);
            $sheet->setCellValue('A' . $this->row_count, $packaging_option->packaging_type);
            $sheet->setCellValue('B' . $this->row_count, UnitHelper::convertTo($packaging_option->max_fill_volume, 'pL', 'L'));
            $sheet->setCellValue('C' . $this->row_count, ($item_packaging_option->cost) / 100);
            $sheet->setCellValue('D' . $this->row_count, ($item_packaging_option->price) / 100);
            $sheet->setCellValue('E' . $this->row_count, $item_packaging_option->quantity);
            $sheet->setCellValue('H' . $this->row_count, '=C' . $this->row_count . '*E' . $this->row_count);
            $sheet->setCellValue('I' . $this->row_count, '=D' . $this->row_count . '*E' . $this->row_count);
            $sheet->setCellValue('J' . $this->row_count, '=((I' . $this->row_count . '-H' . $this->row_count . ')/I' . $this->row_count . ')');
            $this->row_count++;
        }
        // applying border outline for the packaging options
        $break2 = $this->row_count - 1;
        $sheet->getStyle('A' . $break . ':J' . $break2)->applyFromArray($this->border_styles);

        // sum of the packaging option cell values and adding styles
        $summation_end_row = $this->row_count - 1;
        $sheet->mergeCells('A' . $this->row_count . ':G' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':G' . $this->row_count)->applyFromArray($this->total_values_text_styles);
        $sheet->setCellValue('A' . $this->row_count, 'Total Packaging Price');
        $sheet->setCellValue('H' . $this->row_count, '=SUM(H' . $summation_start_row . ':H' . $summation_end_row . ')');
        $sheet->setCellValue('I' . $this->row_count, '=SUM(I' . $summation_start_row . ':I' . $summation_end_row . ')');
        $sheet->setCellValue('J' . $this->row_count, '=((I' . $this->row_count . '-H' . $this->row_count . ')/I' . $this->row_count . ')');
        $sheet->getStyle('H' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->total_price_styles);
        $this->packaging_options_total_price_row = $this->row_count;
        $this->packaging_quantity_rows = 'E' . $summation_start_row . ':E' . $summation_end_row;
        $this->row_count += 2;
    }

    public function writeTestingOptionsToSheet($sheet, $item_qc_testing_methods)
    {
        // fill testing pricing and styling
        $sheet->mergeCells('A' . $this->row_count . ':J' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->pricing_headers_styles);
        $sheet->setCellValue('A' . $this->row_count, 'Testing Pricing');
        $this->row_count++;
        $testing_columns = array('QC Test', 'QC Test Method', 'Cost', 'Price', '', '', '', 'Total Testing Cost', 'Total Testing Price', 'Final Margin');
        $sheet->fromArray($testing_columns, NULL, 'A' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->column_styles);
        $this->row_count++;
        $break = $this->row_count - 1;
        $summation_start_row = $this->row_count;

        // setting cell values of testing options and styling
        foreach ($item_qc_testing_methods as $item_qc_testing_method) {
            $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->font_styles);
            $qc_test_method = is_null($item_qc_testing_method->qc_test_method_id) ? $item_qc_testing_method : QcTestMethod::withTrashed()->get()->firstOrFail('id', $item_qc_testing_method->qc_test_method_id);
            $qc_test = QcTest::withTrashed()->get()->firstOrFail('id', $qc_test_method->qc_test_id);
            $sheet->setCellValue('A' . $this->row_count, $qc_test->name);
            $sheet->setCellValue('B' . $this->row_count, $qc_test_method->name);
            $sheet->setCellValue('C' . $this->row_count, ($item_qc_testing_method->cost) / 100);
            $sheet->setCellValue('D' . $this->row_count, ($item_qc_testing_method->price) / 100);
            $sheet->setCellValue('H' . $this->row_count, '=C' . $this->row_count);
            $sheet->setCellValue('I' . $this->row_count, '=D' . $this->row_count);
            $sheet->setCellValue('J' . $this->row_count, '=((I' . $this->row_count . '-H' . $this->row_count . ')/I' . $this->row_count . ')');
            $this->row_count++;
        }

        // applying border outline for the testing options
        $break2 = $this->row_count - 1;
        $sheet->getStyle('A' . $break . ':J' . $break2)->applyFromArray($this->border_styles);

        // sum of the testing option cell values and adding styles
        $summation_end_row = $this->row_count - 1;
        $sheet->mergeCells('A' . $this->row_count . ':G' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':G' . $this->row_count)->applyFromArray($this->total_values_text_styles);
        $sheet->setCellValue('A' . $this->row_count, 'Total Testing Price');
        $sheet->setCellValue('H' . $this->row_count, '=SUM(H' . $summation_start_row . ':H' . $summation_end_row . ')');
        $sheet->setCellValue('I' . $this->row_count, '=SUM(I' . $summation_start_row . ':I' . $summation_end_row . ')');
        $sheet->setCellValue('J' . $this->row_count, '=((I' . $this->row_count . '-H' . $this->row_count . ')/I' . $this->row_count . ')');
        $sheet->getStyle('H' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->total_price_styles);
        $this->testing_options_total_price_row = $this->row_count;
        $this->row_count += 2;
    }

    public function writeAddonPricingToSheet($sheet, $item_pricing_addon_tiers, $item_packaging_options)
    {
        // fill Add-ons pricing and styling
        $sheet->mergeCells('A' . $this->row_count . ':J' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->pricing_headers_styles);
        $sheet->setCellValue('A' . $this->row_count, 'Add-ons Pricing');
        $this->row_count++;
        $testing_columns = array('Add-on Cost Type', 'Add-on Criteria', 'Unit Cost', 'Unit Price', '', '', '', 'Total Cost', 'Total Price', 'Final Margin');
        $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->column_styles);
        $sheet->fromArray($testing_columns, NULL, 'A' . $this->row_count);
        $this->row_count++;
        $break = $this->row_count - 1;
        $summation_start_row = $this->row_count;

        foreach ($item_packaging_options as $item_packaging_option) {
            $packaging_option = PackagingOption::unScoped()->withTrashed()->get()->firstOrFail('id', $item_packaging_option->packaging_option_id);
            $packaging_option_name = $packaging_option->packaging_type;
        }

        // setting cell values of Add-ons pricing and styling
        foreach ($item_pricing_addon_tiers as $item_pricing_addon_tier) {
            $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->font_styles);
            $pricing_addon = PricingAddon::withTrashed()->get()->firstOrFail('id', $item_pricing_addon_tier->pricing_addon_id);
            $pricing_addon_tiers = PricingAddonTier::withTrashed()->get()->firstOrFail('id', $item_pricing_addon_tier->pricing_addon_tier_id);
            $sheet->setCellValue('A' . $this->row_count, $pricing_addon->name);
            $sheet->setCellValue('B' . $this->row_count, ($packaging_option_name == 'Pod') ? 'No. of Ingredients Changed > ' .  $pricing_addon_tiers->condition_greater_than : 'Total Order Size > ' . $pricing_addon_tiers->condition_greater_than);
            $sheet->setCellValue('C' . $this->row_count, ($item_pricing_addon_tier->cost) / 100);
            $sheet->setCellValue('D' . $this->row_count, ($item_pricing_addon_tier->price) / 100);

            switch ($pricing_addon->conditional_variable) {
                case ('num_liters'):
                    $quantity = 'J2';
                    break;
                case ('num_units'):
                    $quantity = 'SUM(' . $this->packaging_quantity_rows . ')';
                    break;
                case ('num_packaging_options'):
                    $quantity = count($item_packaging_options);
                    break;
                case ('num_ingredients_changed'):
                    $quantity = 'SUM(' . $this->packaging_quantity_rows . ')';
                    break;
            }
            switch ($pricing_addon->pricing_type) {
                case ('linear'):
                    $sheet->setCellValue('H' . $this->row_count, '=C' . $this->row_count . '*' . $quantity);
                    $sheet->setCellValue('I' . $this->row_count, '=D' . $this->row_count . '*' . $quantity);
                    break;
                case ('variable'):
                    $sheet->setCellValue('H' . $this->row_count, ($pricing_addon->name == 'Pod Labour ') ? '=C' . $this->row_count . '*' . $quantity : '=C' . $this->row_count);
                    $sheet->setCellValue('I' . $this->row_count, ($pricing_addon->name == 'Pod Labour ') ? '=D' . $this->row_count . '*' . $quantity : '=D' . $this->row_count);
                    break;
            }
            $sheet->setCellValue('J' . $this->row_count, '=((I' . $this->row_count . '-H' . $this->row_count . ')/I' . $this->row_count . ')');
            $this->row_count++;
        }

        // applying border outline for the Add-ons pricing
        $break2 = $this->row_count - 1;
        $sheet->getStyle('A' . $break . ':J' . $break2)->applyFromArray($this->border_styles);

        // sum of the add-ons pricing cell values and adding styles
        $summation_end_row = $this->row_count - 1;
        $sheet->mergeCells('A' . $this->row_count . ':G' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':G' . $this->row_count)->applyFromArray($this->total_values_text_styles);
        $sheet->setCellValue('A' . $this->row_count, 'Total Add-on Price');
        $sheet->setCellValue('H' . $this->row_count, '=SUM(H' . $summation_start_row . ':H' . $summation_end_row . ')');
        $sheet->setCellValue('I' . $this->row_count, '=SUM(I' . $summation_start_row . ':I' . $summation_end_row . ')');
        $sheet->setCellValue('J' . $this->row_count, '=((I' . $this->row_count . '-H' . $this->row_count . ')/I' . $this->row_count . ')');
        $sheet->getStyle('H' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->total_price_styles);
        $this->pricing_addon_total_price_row = $this->row_count;
    }

    public function writeIngredientListToSheet($sheet, $ingredient_list, $formula_ingredients, $is_liquid = false)
    {
        $unit = $is_liquid ? 'ml' : 'mg';

        if ($is_liquid) {
            $this->row_count++;
        }

        // fill ingredients data and styling
        $ingredient_columns = array('Ingredient ID', 'Ingredient Name', 'Unit Cost (per 1 ' . $unit . ')', 'Unit Price (per 1 ' . $unit . ')', 'Quantity (' . $unit . '/L)', 'Cost Per Liter', 'Price Per Liter', 'Final Cost', 'Final Price', 'Final Margin');
        $sheet->fromArray($ingredient_columns, NULL, 'A' . $this->row_count);
        $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->column_styles);
        $this->row_count++;

        // looping array and setting cell values
        foreach ($ingredient_list as $ingredient_type_name => $ingredients) {
            $sheet->mergeCells('A' . $this->row_count . ':J' . $this->row_count);
            $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->ingredient_types_styles);
            $sheet->setCellValue('A' . $this->row_count, $ingredient_type_name);
            $this->row_count++;
            foreach ($ingredients as $ingredient) {
                $formula_ingredient = $formula_ingredients->firstWhere('ingredient_id', $ingredient->id);

                $cost = UnitHelper::convertTo(
                    $formula_ingredient->cost,
                    $is_liquid ? 'mL' : 'mg',
                    $formula_ingredient->pricing_unit
                );

                $price = UnitHelper::convertTo(
                    $formula_ingredient->price,
                    $is_liquid ? 'mL' : 'mg',
                    $formula_ingredient->pricing_unit
                );

                $quantity = UnitHelper::convertTo(
                    $formula_ingredient->quantity,
                    $is_liquid ? UnitHelper::getDefaultVolumeUnit() : UnitHelper::getDefaultMassUnit(),
                    $is_liquid ? 'mL' : 'mg'
                );

                $sheet->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->font_styles);
                $sheet->setCellValue('A' . $this->row_count, $ingredient->id);
                $sheet->setCellValue('B' . $this->row_count, $ingredient->name);
                $sheet->setCellValue('C' . $this->row_count, $cost->dividedBy('100', 15));
                $sheet->setCellValue('D' . $this->row_count, $price->dividedBy('100', 15));
                $sheet->setCellValue('E' . $this->row_count, $quantity);
                $sheet->setCellValue('F' . $this->row_count, '=C' . $this->row_count . '*E' . $this->row_count);
                $sheet->setCellValue('G' . $this->row_count, '=D' . $this->row_count . '*E' . $this->row_count);
                $sheet->setCellValue('H' . $this->row_count, '=F' . $this->row_count . '*J2');
                $sheet->setCellValue('I' . $this->row_count, '=G' . $this->row_count . '*J2');
                $sheet->setCellValue('J' . $this->row_count, '=((I' . $this->row_count . '-H' . $this->row_count . ')/I' . $this->row_count . ')');
                $this->row_count++;
            }
        }
    }

    public function createAddonSheet($spreadsheet)
    {
        //creating addon sheet
        $sheet2 =  $spreadsheet->createSheet();
        $sheet2->setTitle('Pricing Add-on List');
        $this->row_count = 2;
        $sheet2->getStyle('C:D')->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        $pricing_addon_tiers = PricingAddonTier::withTrashed()->get();
        $addon_headers = array('Add-on Cost Type', 'Add-on Criteria', 'Cost', 'Price', 'Conditional Variable', 'Pricing Type', 'Cost Type', 'Customer Visible', 'Enabled', 'Conditional');
        $sheet2->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->column_styles);
        $sheet2->fromArray($addon_headers, NULL, 'A' . $this->row_count);
        $this->row_count++;
        $break = $this->row_count - 1;

        foreach ($pricing_addon_tiers as $pricing_addon_tier) {
            $pricing_addon = PricingAddon::withTrashed()->get()->firstOrFail('id', $pricing_addon_tier->pricing_addon_id);
            $sheet2->getStyle('A' . $this->row_count . ':J' . $this->row_count)->applyFromArray($this->font_styles);
            $sheet2->setCellValue('A' . $this->row_count, $pricing_addon->name);
            $sheet2->setCellValue('B' . $this->row_count, 'Total Order Size > ' . $pricing_addon_tier->condition_greater_than);
            $sheet2->setCellValue('C' . $this->row_count, ($pricing_addon_tier->cost) / 100);
            $sheet2->setCellValue('D' . $this->row_count, ($pricing_addon_tier->price) / 100);
            $sheet2->setCellValue('E' . $this->row_count, $pricing_addon->conditional_variable);
            $sheet2->setCellValue('F' . $this->row_count, $pricing_addon->pricing_type);
            $sheet2->setCellValue('G' . $this->row_count, $pricing_addon->cost_type);
            $sheet2->setCellValue('H' . $this->row_count, $pricing_addon->is_customer_visible);
            $sheet2->setCellValue('I' . $this->row_count, $pricing_addon->is_enabled);
            $sheet2->setCellValue('J' . $this->row_count, $pricing_addon->is_conditional);
            $this->row_count++;
        }
        $break2 = $this->row_count - 1;
        $sheet2->getStyle('A' . $break . ':J' . $break2)->applyFromArray($this->border_styles);

        for ($i = 1; $i < $this->row_count; $i++) {
            $column = $this->getExcelColumn($i);
            $sheet2->getColumnDimension($column)->setAutoSize(true);
        }
    }

    function getExcelColumn($number)
    {
        $column_string = "";
        $column_number = $number;
        while ($column_number > 0) {
            $current_letter_number = ($column_number - 1) % 26;
            $current_letter = chr($current_letter_number + 65);
            $column_string = $current_letter . $column_string;
            $column_number = ($column_number - ($current_letter_number + 1)) / 26;
        }
        return $column_string;
    }
}
