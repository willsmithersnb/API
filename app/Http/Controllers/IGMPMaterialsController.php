<?php

namespace App\Http\Controllers;

use App\Http\Requests\IGMPMaterialsRequest;
use App\Models\Ingredient;
use App\Models\Material;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Support\Facades\DB;

class IGMPMaterialsController extends Controller
{
    use Helpers;

    /**
     * Handle the incoming request.
     *
     * @param  App\Http\Requests\IGMPMaterialsRequest  $iGMPMaterialsRequest
     * @return \Illuminate\Http\Response
     */
    public function __invoke(IGMPMaterialsRequest $iGMPMaterialsRequest)
    {
        DB::beginTransaction();
        $ingredients = Ingredient::all();
        try {
            foreach ($iGMPMaterialsRequest->get('igmp_materials') as $material) {
                $saved_material = Material::updateOrCreate(
                    ['igmp_part_num' => $material['igmp_part_num']],
                    [
                        'igmp_material_id' => $material['igmp_material_id'],
                        'igmp_spec_id' => $material['igmp_spec_id'],
                        'igmp_name' => $material['igmp_name'],
                        'igmp_material_description' => $material['igmp_material_description'],
                        'igmp_lead_time' => $material['igmp_lead_time'],
                        'grade' => $material['grade'],
                        'storage_requirement' => $material['storage_requirement'],
                        'reference_num' => $material['reference_num'],
                        'reference_num' => $material['reference_num'],
                        'reference_type' => $material['reference_type'],
                        'price' => $material['price'],
                        'cost' => $material['cost'],
                        'pricing_quantity' => $material['pricing_quantity'],
                        'pricing_unit' => $material['pricing_unit'],
                        'unit_type' => $material['unit_type'],
                        'nb_part_num' => $material['nb_part_num'],
                    ]
                );
                if ($saved_material->reference_num != "N/A") {
                    try {
                        $ingredient = $ingredients->firstOrFail("reference_num", $saved_material->reference_num);
                        $saved_material->materialable_type = "ingredient";
                        $saved_material->materialable_id = $ingredient->id;
                        $saved_material->save();
                    } catch (Exception $ex) {
                    }
                }
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
        }

        return response()->json(['message' => "Imported"]);
    }
}
