<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IGMPMaterialsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'igmp_materials' => 'required|array',
            'igmp_materials.*.igmp_material_id' => 'required|string',
            'igmp_materials.*.igmp_spec_id' => 'required|string',
            'igmp_materials.*.igmp_part_num' => 'required|string',
            'igmp_materials.*.igmp_material_description' => 'required|string',
            'igmp_materials.*.igmp_lead_time' => 'required|integer',
            'igmp_materials.*.grade' => 'required|string',
            'igmp_materials.*.storage_requirement' => 'required|string',
            'igmp_materials.*.reference_num' => 'required|string',
            'igmp_materials.*.reference_type' => 'required|string',
            'igmp_materials.*.price' => 'required|integer',
            'igmp_materials.*.cost' => 'required|integer',
            'igmp_materials.*.pricing_quantity' => 'required|integer',
            'igmp_materials.*.unit_type' => 'required|integer',
            'igmp_materials.*.pricing_unit' => 'required|integer',
            'igmp_materials.*.display_unit' => 'required|string',
            'igmp_materials.*.nb_part_num' => 'required|string'
        ];
    }
}
