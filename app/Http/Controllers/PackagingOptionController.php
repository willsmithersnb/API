<?php

namespace App\Http\Controllers;

use App\Models\PackagingOption;
use App\Transformer\PackagingOptionTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PackagingOptionController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(PackagingOption::class, 'packaging_option');
    }

    /**
     * Returns base rules for dingo validator
     *
     * @return Array rules list
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'price' => 'required|integer|digits_between:0,18',
            'cost' => 'required|integer|digits_between:0,18',
            'packaging_type' => 'required|string|max:20|in:Bag,Bottle,Drum,Container,Pod',
            'max_fill_volume' => 'required|integer',
            'configuration' => 'nullable|json',
            'fill_tolerance' => 'required|integer',
            'fill_unit' => 'required|integer',
            'unit_type' => 'required|integer',
            'basal_enabled' => 'required|boolean',
            'balanced_salt_enabled' => 'required|boolean',
            'buffer_enabled' => 'required|boolean',
            'cryo_enabled' => 'required|boolean',
            'customer_id' => 'sometimes|nullable|integer',
            'moq' => 'required|integer'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $packaging_options =  PackagingOption::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $packaging_options->paginate();
            return $this->response->paginator($data, new PackagingOptionTransformer);
        } else {
            $data = $packaging_options->get();
            return $this->response->collection($data, new PackagingOptionTransformer);
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
            $object_hash = $request->all();
            $hash_array =  $request->only(PackagingOption::$hashable);
            if ($request->get('packaging_type') == 'Bag') {
                $hash_array['ports_per_bag'] = json_decode($request->get('configuration'))->ports_per_bag;
            }
            $data = array_merge($request->only(array_keys($this->rules())), ['packaging_hash' => md5(implode('|', $hash_array)), 'object_hash' => md5(implode('|', $object_hash))]);
            $packaging_option = PackagingOption::create($data);

            return $this->response->item($packaging_option, new PackagingOptionTransformer);
        } catch (\Exception $ex) {
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  PackagingOption $packaging_option
     * @return \Illuminate\Http\Response
     */
    public function show(PackagingOption $packaging_option)
    {
        try {
            return $this->response->item($packaging_option, new PackagingOptionTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PackagingOption $packaging_option
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PackagingOption $packaging_option)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $hash_array =  $request->only(PackagingOption::$hashable);
            if ($request->get('packaging_type') == 'Bag') {
                $hash_array['ports_per_bag'] = json_decode($request->get('configuration'))->ports_per_bag;
            }
            $data = array_merge($request->only(array_keys($this->rules())), ['packaging_hash' => md5(implode('|', $hash_array))]);
            $packaging_option->update($data);
            return $this->response->item($packaging_option, new PackagingOptionTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PackagingOption $packaging_option
     * @return \Illuminate\Http\Response
     */
    public function destroy(PackagingOption $packaging_option)
    {
        try {
            $packaging_option->delete();
            return $this->response->item($packaging_option, new PackagingOptionTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
