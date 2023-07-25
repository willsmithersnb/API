<?php

namespace App\Exports;

use App\Models\Ingredient;
use App\Models\IngredientType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class IngredientsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Ingredient::orderBy('id', 'ASC')->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Created At', 'Updated At', 'Name', 'Molecular Mass',  'Osmolality', 'Min Quantity', 'Max Quantity', 'Reference Number', 'Reference Type', 'Display Unit', 'Unit Type', 'Url', 'Basal Enabled', 'Balanced Salt Enabled', 'Buffer Enabled', 'Cryo Enabled', 'Pricing Unit', 'Price (Cents)', 'Cost (Cents)', 'Ingredient Type'
        ];
    }

    public function map($ingredients): array
    {
        $units = config('enums.units');
        $unit_types = config('enums.display_unit_types');
        $ingredient_types = IngredientType::get()->firstOrFail('id', $ingredients->ingredient_type_id);
        return [
            $ingredients->id,
            Date::dateTimeToExcel($ingredients->created_at),
            Date::dateTimeToExcel($ingredients->updated_at),
            $ingredients->name,
            strval($ingredients->molecular_mass),
            strval($ingredients->osmolality),
            strval($ingredients->min_quantity),
            strval($ingredients->max_quantity),
            $ingredients->reference_num,
            $ingredients->reference_type,
            $units[$ingredients->display_unit],
            $unit_types[$ingredients->unit_type],
            $ingredients->url,
            ($ingredients->basal_enabled == FALSE) ? 'FALSE' : 'TRUE',
            ($ingredients->balanced_salt_enabled == FALSE) ? 'FALSE' : 'TRUE',
            ($ingredients->buffer_enabled == FALSE) ? 'FALSE' : 'TRUE',
            ($ingredients->cryo_enabled == FALSE) ? 'FALSE' : 'TRUE',
            $units[$ingredients->pricing_unit],
            strval($ingredients->price),
            strval($ingredients->cost),
            $ingredient_types->name
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DATETIME,
            'C' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

}
