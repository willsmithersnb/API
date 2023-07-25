<?php

namespace App\Http\Controllers;

use App\Helper\UnitHelper;
use App\Models\Formula;
use App\Models\Ingredient;
use App\Models\ItemPackagingOption;
use App\Models\Order;
use App\Models\PackagingOption;
use App\Models\Quote;
use App\Models\QcTest;
use Brick\Math\RoundingMode;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Http\Request;
use NcJoes\OfficeConverter\OfficeConverter;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\TemplateProcessor;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class SpecificationSheetGeneratorController extends Controller
{
    private $powderTable = null;
    private $liquidTable = null;
    private $customTable = null;
    private $testingTable = null;
    private $totalPowderQuantity = 0;
    private $totalLiquidQuantity = 0;
    private $table_bolded_template_style = ['bold' => true, 'name' => 'arial', 'size' => 8];
    private $table_header_cell_styles = ['bgColor' => 'D9D9D9'];
    private $table_detail_cell_style = ['name' => 'arial', 'size' => 8];

    public function initIngredientTable($table_type)
    {
        $table = new Table(array('borderSize' => 4, 'width' => 7500, 'unit' => TblWidth::TWIP));

        $table->addRow(60);

        // Part Number
        $table->addCell(200, $this->table_header_cell_styles)->addText('Part Number', $this->table_bolded_template_style);
        // Part Name
        $table->addCell(400, $this->table_header_cell_styles)->addText('Part Name', $this->table_bolded_template_style);
        // CAS#
        $table->addCell(200, $this->table_header_cell_styles)->addText('CAS# ', $this->table_bolded_template_style);
        // Grade/Purity
        $table->addCell(400, $this->table_header_cell_styles)->addText('Grade/Purity', $this->table_bolded_template_style);
        if ($table_type == 'powder') {
            $unit = 'g';
            $this->powderTable = $table;
        } else {
            $unit = 'mL';
            $this->liquidTable = $table;
        }
        $table->addCell(400, $this->table_header_cell_styles)->addText('Concentration (' . $unit . '/L)', $this->table_bolded_template_style);
    }

    public function initCustomComponentTable()
    {
        $table = new Table(array('borderSize' => 4, 'width' => 7500, 'unit' => TblWidth::TWIP));
        $table->addRow(60);

        //Component name
        $table->addCell(200, $this->table_header_cell_styles)->addText('Component Name', $this->table_bolded_template_style);
        $this->customTable = $table;
    }

    public function initTestingTable()
    {
        $table = new Table(array('borderSize' => 4, 'width' => 7500, 'unit' => TblWidth::TWIP));

        $table->addRow(60);

        // Part Number
        $table->addCell(150, $this->table_header_cell_styles)->addText('Test', $this->table_bolded_template_style);
        // Part Name
        $table->addCell(130, $this->table_header_cell_styles)->addText('Method', $this->table_bolded_template_style);
        // CAS#
        $table->addCell(300, $this->table_header_cell_styles)->addText('Specification', $this->table_bolded_template_style);

        $this->testingTable = $table;
    }

    public function initPackagingBlock(TemplateProcessor $templateProcessor, int $option_count)
    {
        if ($option_count == 0) {
            $templateProcessor->cloneBlock('packaging_option', 0, true, true);
        } else {
            $templateProcessor->cloneBlock('packaging_option', $option_count, true, true);
        }
    }

    public function fillPackagingBlock(TemplateProcessor $templateProcessor, ItemPackagingOption $itemPackagingOption, int $option_index)
    {
        $packaging_materials = PackagingOption::unScoped()->withTrashed()->get()->firstOrFail('id', $itemPackagingOption->packaging_option_id)->materials()->get();
        $packaging_option_table = new Table(array('borderSize' => 4, 'width' => 7500, 'unit' => TblWidth::TWIP));

        $packaging_option_table->addRow(60);

        // Part Number
        $packaging_option_table->addCell(200, $this->table_header_cell_styles)->addText('Part Number', $this->table_bolded_template_style);
        // Part Name
        $packaging_option_table->addCell(500, $this->table_header_cell_styles)->addText('Description', $this->table_bolded_template_style);

        if ($packaging_materials->count() < 0) {
            $packaging_option_table->addRow();
            $packaging_option_table->addCell(50)->addText('TBD', $this->table_detail_cell_style);
            $packaging_option_table->addCell(50)->addText('', $this->table_detail_cell_style);
        }
        foreach ($packaging_materials as $packaging_material) {
            $packaging_option_table->addRow();
            $packaging_option_table->addCell(50)->addText($packaging_material->igmp_part_num, $this->table_detail_cell_style);
            $packaging_option_table->addCell(100)->addText($packaging_material->igmp_material_description, $this->table_detail_cell_style);
        }

        $templateProcessor->setValues(
            [
                'fill_volume#' . $option_index => UnitHelper::convertTo(
                    $itemPackagingOption->fill_amount,
                    UnitHelper::getDefaultVolumeUnit(),
                    'L'
                )->toScale(5, RoundingMode::UP),
                'fill_tolerance#' . $option_index => UnitHelper::convertTo(
                    $itemPackagingOption->fill_tolerance,
                    UnitHelper::getDefaultVolumeUnit(),
                    'L'
                )->toScale(5, RoundingMode::UP),
                'max_fill_volume#' . $option_index => UnitHelper::convertTo(
                    $itemPackagingOption->max_fill_volume,
                    UnitHelper::getDefaultVolumeUnit(),
                    'L'
                )->toScale(5, RoundingMode::UP),
            ]
        );

        $templateProcessor->setComplexBlock('packaging_table#' . $option_index, $packaging_option_table);
    }

    private function getIngredientTableByType($table_type)
    {
        if ($table_type == 'powder') {
            return $this->powderTable;
        }
        return $this->liquidTable;
    }

    public function addIngredientSummaryRow($table_type, $template, $quantity, $quantity_unit, $unit)
    {
        $table = $this->getIngredientTableByType($table_type);
        $table->addRow(300);
        $cell = $table->addCell(200);
        $cell->getStyle()->setGridSpan(4);
        $cell->addText($template, $this->table_bolded_template_style);

        $table->addCell(200)->addText(
            UnitHelper::convertTo(
                $quantity,
                $quantity_unit,
                $unit
            ) . " " . $unit . "/L",
            $this->table_bolded_template_style
        );
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, int $id)
    {
        $document_name = $request->get('doc_name');
        $items = ($document_name == 'order') ? Order::unScoped()->findOrFail($id)->itemList()->unScoped()->first()->items()->unScoped()->get() : Quote::unScoped()->findOrFail($id)->itemList()->unScoped()->first()->items()->unScoped()->get();
        $formula_ingredients = $items[0]->formula()->unScoped()->first()->formulaIngredients()->unScoped()->get();

        $download_format = $request->get('format', 'docx');

        $template_name = 'spec_template.docx';

        if ($download_format != 'docx') {
            $template_name = 'spec_template_draft.docx';
        }

        $document = new TemplateProcessor(Storage::disk('local')->path($template_name));

        $this->initIngredientTable('powder');
        $this->initIngredientTable('liquid');
        $this->initCustomComponentTable();

        foreach ($formula_ingredients as $formula_ingredient) {
            $table_type = $this->getIngredientTableByType($formula_ingredient->unit_type == 0 ? 'powder' : 'liquid');

            $table_type->addRow();
            $ingredient = $formula_ingredient->ingredient;

            $part_numbers_cell = $table_type->addCell(50);
            $part_name_cell = $table_type->addCell(400);
            $cas_no_cell = $table_type->addCell(100);
            $purity_cell = $table_type->addCell(400);
            $concentration_cell = $table_type->addCell(100);

            $cas_no_cell->addText($ingredient->reference_num, $this->table_detail_cell_style);
            if ($formula_ingredient->unit_type == 0) {
                $this->totalPowderQuantity += $formula_ingredient->quantity;
            } else {
                $this->totalLiquidQuantity += $formula_ingredient->quantity;
            }
            $concentration_cell->addText(UnitHelper::convertTo(
                $formula_ingredient->quantity,
                UnitHelper::getDefaultMassUnit(),
                'g'
            ), $this->table_detail_cell_style);
            $materials = $ingredient->materials()->get();

            foreach ($materials as $material) {
                $part_numbers_cell->addText($material->igmp_part_num, $this->table_detail_cell_style);

                $part_name_cell->addText($material->igmp_name, $this->table_detail_cell_style);

                $purity_cell->addText($material->grade, $this->table_detail_cell_style);
            }
        }

        $this->addIngredientSummaryRow('powder', "Total weight of dry components:", $this->totalPowderQuantity, UnitHelper::getDefaultMassUnit(), 'g');
        $this->addIngredientSummaryRow('liquid', "Total volume of liquid components:", $this->totalLiquidQuantity, UnitHelper::getDefaultVolumeUnit(), 'mL');

        $document->setComplexBlock('powder_components', $this->powderTable);
        $document->setComplexBlock('liquid_components', $this->liquidTable);

        if ($document_name != 'order') {
            $quote = Quote::unScoped()->findOrFail($id);
            $custom_components = json_decode($quote->custom_components);
            if (!empty($custom_components)) {
                $document->cloneBlock('custom_components', 1, true, true);
                foreach ($custom_components as $custom_component) {
                    $this->customTable->addRow();
                    $custom_component_name_cell = $this->customTable->addCell(100);
                    $custom_component_name_cell->addText($custom_component->name, $this->table_detail_cell_style);
                }
                $document->setComplexBlock('custom_components_table#1', $this->customTable);
            } else {
                $document->cloneRow('custom_components', 0);
            }
        } else {
            $document->cloneRow('custom_components', 0);
        }

        $testingOptions = $items[0]->itemQcTestMethod()->unScoped()->get();
        if ($testingOptions->count() == 0) {
            $document->cloneBlock('testing_options', 0, true, true);
        } else {
            $document->cloneBlock('testing_options', 1, true, true);
            $this->initTestingTable();

            foreach ($testingOptions as $testingOption) {
                $qcTest = QcTest::withTrashed()->get()->firstOrFail('id', $testingOption->qc_test_id);
                $this->testingTable->addRow();
                $test_cell = $this->testingTable->addCell(50);
                $method_cell = $this->testingTable->addCell(400);
                $specification_cell = $this->testingTable->addCell(100);
                $test_cell->addText($qcTest->name, $this->table_detail_cell_style);
                $specification  = '';
                $value_decode = json_decode($testingOption->value);
                if (! empty($value_decode)) {
                    switch ($qcTest->ui_component_name) {
                        case 'target-ph':
                            $specification = $value_decode->targetPhMin . ' - ' . $value_decode->targetPhMax;
                            break;
                        case 'osmolality':
                            $specification = $value_decode->osmolalityRange[0] . ' mOsm/kg  - ' . $value_decode->osmolalityRange[1] . ' mOsm/kg ';
                            break;
                        case 'endotoxin':
                            $specification = $value_decode->endotoxinValue;
                            break;
                        case 'b-input':
                            $specification = $value_decode->text;
                            break;
                        default:
                            $specification = '-';
                            break;
                    }
                } else {
                    $specification = '-';
                }

                $method_cell->addText(htmlspecialchars(optional($testingOption->qcTestMethod)->name), $this->table_detail_cell_style);
                $specification_cell->addText($specification, $this->table_detail_cell_style);
            }
            $document->setComplexBlock('testing_options_table#1', $this->testingTable);
        }

        $itemPackagingOptions = $items[0]->itemPackagingOptions()->unScoped()->get();
        $this->initPackagingBlock($document, $itemPackagingOptions->count());

        $packagingOptionIndex = 0;
        foreach ($itemPackagingOptions as $itemPackagingOption) {
            $packagingOptionIndex++;
            $this->fillPackagingBlock($document, $itemPackagingOption, $packagingOptionIndex);
        }

        $name = ($document_name == 'order') ? 'Order#' . $id : 'Quote#' . $id;

        if ($download_format == 'docx') {
            // TODO: add request validation
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $name . ' Specification Sheet.docx"');

            $document->saveAs('php://output');
        } else {
            $temporaryDirectory = (new TemporaryDirectory())->create();
            // Get a path inside the temporary directory
            $tempWord = $temporaryDirectory->path('temporaryfile.docx');
            $tempPath = $temporaryDirectory->path();

            $temp = 'result.docx';
            $document->saveAs($tempWord);

            $converter = new OfficeConverter($tempWord, $tempPath, config('app.libre_office'), false);
            $converter->convertTo('result.pdf');
            return response()->file($tempPath . DIRECTORY_SEPARATOR . 'result.pdf', [
                'Content-Type' => 'application/pdf',
                'Content-disposition' => 'inline; filename="' . $name . ' Draft Specification Sheet.pdf"'
            ]);
        }
    }
}
