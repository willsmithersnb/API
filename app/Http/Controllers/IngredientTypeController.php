<?php

namespace App\Http\Controllers;

use App\Models\IngredientType;
use App\Transformer\ModelTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use App\Transformer\IngredientTypeTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IngredientTypeController extends Controller
{
    use Helpers;

    /**
     * Returns base rules for dingo validator
     *
     * @return Array rules list
     */
    private function rules()
    {
        return [
            'order' => 'required|integer',
            'name' => 'required|string|max:190'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ingredientTypes = IngredientType::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $ingredientTypes->paginate();
            return $this->response->paginator($data, new IngredientTypeTransformer);
        } else {
            $data = $ingredientTypes->get();
            return $this->response->collection($data, new IngredientTypeTransformer);
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
            $ingredient = IngredientType::create($request->only(array_keys($this->rules())));
            return $this->response->item($ingredient, new IngredientTypeTransformer);
        } catch (\Exception $ex) {
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  IngredientType  $ingredientType
     * @return \Illuminate\Http\Response
     */
    public function show(IngredientType $ingredientType)
    {
        try {
            return $this->response->item($ingredientType, new IngredientTypeTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  IngredientType $ingredientType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IngredientType $ingredientType)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new UpdateResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $ingredientType->update($request->only(array_keys($this->rules())));
            return $this->response->item($ingredientType, new IngredientTypeTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  IngredientType $ingredientType
     * @return \Illuminate\Http\Response
     */
    public function destroy(IngredientType $ingredientType)
    {
        try {
            $ingredientType->delete();
            return $this->response->item($ingredientType, new IngredientTypeTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
