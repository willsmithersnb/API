<?php

namespace App\Http\Controllers;

use App\Exports\IngredientsExport;
use App\Models\Ingredient;
use App\Transformer\IngredientTransformer;

use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Excel;
use Illuminate\Support\Facades\URL;

class IngredientController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(Ingredient::class, 'ingredient');
    }

    /**
     * Returns base rules for dingo validator
     *
     * @return Array rules list
     */
    private function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'ingredient_type_id' => 'required|integer|exists:App\Models\IngredientType,id',
            'molecular_mass' => 'required|integer|digits_between:0,18',
            'osmolality' => 'required|integer|digits_between:0,18',
            'min_quantity' => 'required|integer|digits_between:0,18',
            'max_quantity' => 'required|integer|digits_between:0,18',
            'reference_num' => 'required|string|max:150',
            'reference_type' => 'required|string|max:150',
            'display_unit' => 'required|integer|digits_between:0,6',
            'pricing_unit' => 'required|integer|digits_between:0,6',
            'unit_type' => 'sometimes|integer',
            'price' => 'required|integer|digits_between:0,18',
            'cost' => 'required|integer|digits_between:0,18',
            'url' => 'sometimes|string|url|nullable|max:150',
            'basal_enabled' => 'boolean',
            'balanced_salt_enabled' => 'boolean',
            'buffer_enabled' => 'boolean',
            'cryo_enabled' => 'boolean',
            'prestashop_name' => 'sometimes|string|max:150',
            'podable' => 'sometimes|boolean',
            'is_enabled' => 'sometimes|boolean'
        ];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ingredients = Ingredient::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $ingredients->paginate();
            return $this->response->paginator($data, new IngredientTransformer);
        } else {
            $data = $ingredients->get();
            return $this->response->collection($data, new IngredientTransformer);
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
            $ingredient = Ingredient::create($request->only(array_keys($this->rules())));
            return $this->response->item($ingredient, new IngredientTransformer);
        } catch (\Exception $ex) {
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ingredient $ingredient)
    {
        try {
            return $this->response->item($ingredient, new IngredientTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new UpdateResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $ingredient->update($request->only(array_keys($this->rules())));
            return $this->response->item($ingredient, new IngredientTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {
        try {
            $formulaIngredient = $ingredient->formulaIngredients()->get();
            if ($formulaIngredient->count() > 0) {
                return response()->json(['status' => 400, 'message' => 'Cannot delete component. This component is currently linked to an order. Please remove the component from all the orders before attempting to delete it.'], 400);
            }
            $ingredient->delete();
            return $this->response->item($ingredient, new IngredientTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }

    public function tempUrlIngredientList()
    {
        if (isAdminOriginated()) {
            $response = URL::temporarySignedRoute(
                'ingredient-list-export',
                now()->addMinutes(config('app.temp_url_ttl', 1)),
            );
            return response()->json(['data' => ['url' => $response]], 200);
        } else {
            abort(404);
        }
    }

    public function exportCSV()
    {
        $file_name = 'Ingredient List (' . date('m.d.Y') . ').csv';
        return Excel::download(new IngredientsExport, $file_name);
    }
}
