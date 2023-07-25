<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Transformer\MaterialTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MaterialController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(Material::class, 'material');
    }

    /**
     * Returns base rules for dingo validator
     *
     * @return Array rules list
     */
    private function rules()
    {
        return [
            'igmp_material_id' => 'required|string',
            'igmp_spec_id' => 'required|string|max:191',
            'igmp_part_num' => 'required|string|max:191',
            'igmp_name' => 'required|string|max:191',
            'igmp_material_description' => 'required|string|max:1000',
            'igmp_lead_time' => 'required|integer',
            'grade' => 'required|string|max:191',
            'storage_requirement' => 'required|string',
            'reference_num' => 'required|string|max:191',
            'reference_type' => 'required|in:cas_no,cat_no',
            'price' => 'required|integer',
            'cost' => 'required|integer',
            'pricing_quantity' => 'required|integer',
            'pricing_unit' => 'required|integer|digits_between:0,6',
            'unit_type' => 'required|integer|digits_between:0,1',
            'is_active' => 'boolean',
            'nb_part_num' => 'required|string|max:150',
            'materialable_type' => 'sometimes|string',
            'materialable_id' => 'sometimes|poly_exists:materialable_type'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $materials = Material::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $materials->paginate();
            return $this->response->paginator($data, new MaterialTransformer);
        } else {
            $data = $materials->get();
            return $this->response->collection($data, new MaterialTransformer);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $material = Material::create($request->only(array_keys($this->rules())));
            return $this->response->item($material, new MaterialTransformer);
        } catch (\Exception $ex) {
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        try {
            return $this->response->item($material, new MaterialTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new UpdateResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $material->update($request->only(array_keys($this->rules())));
            return $this->response->item($material, new MaterialTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException();
        } catch (\Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        try {
            $material->delete();
            return $this->response->item($material, new MaterialTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
