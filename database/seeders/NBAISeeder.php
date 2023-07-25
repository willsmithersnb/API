<?php

namespace Database\Seeders;

use App\Helper\UnitHelper;
use Illuminate\Database\Seeder;
use App\Models\CellType;
use App\Models\Ingredient;
use App\Models\NBAI\CellMedia;
use App\Models\NBAI\CellMediaRecommendations;
use App\Models\NBAI\CriticalQualityAttribute;
use App\Models\NBAI\ExpressionType;
use App\Models\NBAI\GeneProteinExpression;
use App\Models\NBAI\GpeRecommendation;
use App\Models\NBAI\Journal;
use App\Models\NBAI\Recommendation;
use App\Models\NBAI\ResearchPaper;
use Brick\Math\BigDecimal;
use Illuminate\Support\Str;

const ERROR_500 = "[ 500 Error ] : ";

class NBAISeeder extends Seeder
{
    private function getConcentration(String $value, int $unit, String $unit_string){
        
        $values_split = explode(',', $value);
        if ($unit !== null) {
            $base_unit = UnitHelper::getDefaultBaseUnit($unit_string);

            if (count($values_split) == 1) {
                $temp_concentration = floatval($values_split[0]);

                $concentration_low = $temp_concentration;
                $concentration_mid = $temp_concentration;
                $concentration_high = $temp_concentration;
            } else if (count($values_split) == 2) {
                $concentration_low = floatval(trim(ltrim($values_split[0])));
                $concentration_mid = floatval(trim(ltrim($values_split[0])));
                $concentration_high = floatval(trim(ltrim($values_split[1])));
            } else if (count($values_split) == 3) {
                $concentration_low = floatval(trim(ltrim($values_split[0])));
                $concentration_mid = floatval(trim(ltrim($values_split[1])));
                $concentration_high = floatval(trim(ltrim($values_split[2])));
            } else if (count($values_split) > 3) {
                $concentration_low = floatval(trim(ltrim($values_split[0])));
                $concentration_mid = floatval(trim(ltrim($values_split[1])));
                $concentration_high = floatval(trim(ltrim($values_split[count($values_split) - 1])));
            }
            if ($concentration_low !== null) {
                return [
                    UnitHelper::convertTo($concentration_low, $unit_string, $base_unit)->toInt(),
                    UnitHelper::convertTo($concentration_mid, $unit_string, $base_unit)->toInt(),
                    UnitHelper::convertTo($concentration_high, $unit_string, $base_unit)->toInt(),
                ];
            } 
        }
        return [null, null,null];
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = fopen('database/seeders/data/data.csv', 'r');
        $line_num = -1;
        while (($line = fgetcsv($file)) !== FALSE) {
            $line_num++;
            if ($line[0] == 'Date' || $line[5] == '-'|| $line[4] == '-' || $line[12] == '' || $line[12] == '-'){
                continue;
            }
            // skip impact factor fuck-ups
            if ($line[16] == '#N/A' || $line[16] == '') {
                continue;
            }

            // Create Journal if not exist
            $journal = Journal::updateOrCreate(
                ['name' => trim(ltrim($line[15]))],
                ['impact_factor' => BigDecimal::of( $line[16] )]
            );
            
            //Get CQA
            $cqa_list = [
                CriticalQualityAttribute::where(['name'=>trim(ltrim($line[6]))])->first(),
                CriticalQualityAttribute::where(['name'=>trim(ltrim($line[10]))])->first(),
                CriticalQualityAttribute::where(['name'=>trim(ltrim($line[11]))])->first()
            ];

            // Create Research Paper if not exist
            $research_paper = ResearchPaper::updateOrCreate(
                [
                    'title' => trim(ltrim($line[19])),
                    'journal_id' => $journal->id,
                    'year' => $line[18],
                ], [
                    'author_name' => trim(ltrim($line[20])),
                    'link' => trim(ltrim($line[1])),
                    'cited_by' => $line[17],
                    'years_out' => $line[21]
                ]
            );

            // Throw error if ingredient is missing
            $ingredient = Ingredient::where('prestashop_name', trim(ltrim($line[3])))->first();
            if($ingredient ===  null){
                echo "[404 Ingredient not found] : Line ".$line_num ." for component " . $line[3] . "\n";
                continue;
            }

            // Create Cell type if not exist
            $cell_type = CellType::firstOrCreate(['name'=> trim(ltrim($line[2]))]);

            $medias = [
                CellMedia::where([
                    'name' => trim(ltrim($line[26]))
                ])->first(),
                CellMedia::where([
                    'name' => trim(ltrim($line[27]))
                ])->first(),
                CellMedia::where([
                    'name' => trim(ltrim($line[28]))
                ])->first(),
            ];

            
            $unit_split = explode('/',$line[5]);
            $unit = null;
            
            if(($unit = array_search($unit_split[0], config('enums.units')) )=== false){
                echo "[404 Unit not found] : Line " . $line_num . " for Unit ". $line[5] . "\n";
                $unit = null;
            }

            if(count($unit_split) > 2 || (count($unit_split) >= 2 && array_search($unit_split[1], array("l","L")) === false)){
                echo ERROR_500 . $line_num . " Error in volume for ". $unit_split[1] .":". json_encode($unit_split) . "\n" ;
            }
            
            if($unit){
                $recommendation = [
                    'cell_type_id' => $cell_type->id,
                    'ingredient_id' => $ingredient->id,
                    'concentration_unit' => $unit,
                    'deviation' => Str::lower(trim(ltrim($line[12]))),
                    'quote' => trim(ltrim($line[25])),
                    'research_paper_id' => $research_paper->id,
                    'score' => floatval($line[24])
                ];
                // define recommendations array 
                $recommendations_per_line = [];

                // init recommendations array 
                foreach($cqa_list as $cqa){
                    if (!is_null($cqa)){
                        
                        $recommendation['cqa_id'] = $cqa->id;
                        
                        if (trim(ltrim($line[4])) == ''){
                            continue;
                        }
                        if ($cqa->name == 'Gene/Protein Expression'){
                            if( trim(ltrim($line[7])) == '' ){
                                continue;
                            }
                            $concentration_split = explode(';',$line[4]);
                            $gpe_split = explode(';', $line[7]);
                            $gpe_count = count($gpe_split);
                            $concentration_count = count($concentration_split);
                            $gpe_exp_split = explode(';',$line[8]);

                            if (($gpe_count != $concentration_count || $gpe_count != count($gpe_exp_split))&&  $line[6] == 'Gene/Protein Expression') {
                                echo ERROR_500 . $line_num . " Error Miss matching num of Concentration or Exp type against GPEs";
                                continue;
                            }

                            if($line[6] != 'Gene/Protein Expression' && $concentration_count > 1){
                                echo ERROR_500 . $line_num . " Error in Concentration";
                                continue;
                            }

                            for ($i=0; $i < $gpe_count ; $i++) {
                                $concentrations = $this->getConcentration($concentration_split[$i], $unit, $unit_split[0]);
                                sort($concentrations);
                                $recommendation['concentration_low'] = $concentrations[0];
                                $recommendation['concentration_mid'] = $concentrations[1];
                                $recommendation['concentration_high'] = $concentrations[2];
                                $created_recommendation = Recommendation::create($recommendation);
                                $gpe = GeneProteinExpression::firstOrCreate(['name' => trim(ltrim($gpe_split[$i]))]);
                                $expression_type = ExpressionType::firstOrCreate(['name'=> trim(ltrim($gpe_exp_split[$i]))]);
                                //create many to many
                                GpeRecommendation::firstOrCreate([
                                    'recommendation_id' => $created_recommendation->id,
                                    'gene_protein_expression_id' => $gpe->id,
                                    'expression_type_id' => $expression_type->id
                                ]);
                                echo json_encode($recommendation);
                                $recommendations_per_line[] = $created_recommendation;
                            }
                            
                        }else{
                            
                            $concentrations = $this->getConcentration($line[4], $unit, $unit_split[0]);
                            sort($concentrations);
                            $recommendation['concentration_low'] = $concentrations[0];
                            $recommendation['concentration_mid'] = $concentrations[1];
                            $recommendation['concentration_high'] = $concentrations[2];
                            echo json_encode($recommendation);
                            $recommendations_per_line[] = Recommendation::create($recommendation);
                        }
                   }
                }

                // Create a link to media 
                foreach ($recommendations_per_line as $rec) {
                    foreach ($medias as $media) {
                        if ($media !== null && $rec !== null) {
                            CellMediaRecommendations::create([
                                'recommendation_id' => $rec->id,
                                'cell_media_id' => $media->id
                            ]);
                        }
                    }
                }
                
            }
            // Print on console to not loose sanity while script runs 
            echo json_encode($recommendations_per_line) . "\n";
        }
        fclose($file);
    }
}
