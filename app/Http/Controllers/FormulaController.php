<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\FormulaIngredient;
use App\Transformer\FormulaTransformer;
use Dingo\Api\Auth\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FormulaController extends ResourceController
{
    protected $model_class = Formula::class;

    protected $url_key = 'formula';

    protected $rule_set = [
        'name' => 'required|string|max:191',
        'parent_id' => 'sometimes|nullable|exists:formulas,id',
        'customer_id' => 'required|exists:App\Models\Customer,id',
        'formula_hash' => 'required|string'
    ];

    public function __construct()
    {
        $this->except = collect([]);
        $this->authorizeResource($this->model_class, $this->url_key, [
            'except' => ['show', 'update'],
        ]);
    }

    protected function transformer()
    {
        return new FormulaTransformer;
    }

    public function store(Request $request)
    {
        $this->except->add('customer_id');
        return parent::storeObject(requestBodyWithCustomerID($request));
    }

    public function show(int $formula_id)
    {
        $formula = Formula::unScoped()->findOrFail($formula_id);
        $this->authorize('view', $formula);
        return parent::showObject($formula);
    }

    public function update(int $formula_id, Request $request)
    {
        $this->except->add('customer_id');
        $formula = Formula::findOrFail($formula_id);
        $this->authorize('update', $formula);
        return parent::updateObject($request, $formula);
    }

    public function destroy(Formula $formula)
    {
        return parent::destroyObject($formula);
    }

    public function duplicateFormula(Request $request)
    {
        if (isAdminOriginated()) {
            DB::beginTransaction();
            $formula = Formula::findOrFail($request->get('formula_id'));
            if (!is_null($formula)) {
                $new_formula = Formula::create([
                    'name' => $request->get('name'),
                    'formula_hash' => $formula->formula_hash,
                    'customer_id' => null,
                    'parent_id' => null
                ]);
                $formula_ingredients = FormulaIngredient::where('formula_id', $request->get('formula_id'))->get();
                foreach ($formula_ingredients as $formula_ingredient) {
                    FormulaIngredient::create([
                        'formula_id' => $new_formula->id,
                        'quantity' => $formula_ingredient->quantity,
                        'quantity_unit' => $formula_ingredient->quantity_unit,
                        'pricing_unit' => $formula_ingredient->pricing_unit,
                        'unit_type' => $formula_ingredient->unit_type,
                        'price' => $formula_ingredient->price,
                        'cost' => $formula_ingredient->cost,
                        'customer_id' => null,
                        'ingredient_id' => $formula_ingredient->ingredient_id
                    ]);
                }
                DB::commit();
                return response()->json(['data' => $new_formula]);
            }
        } else {
            DB::rollBack();
            throw new AuthorizationException();
        }
    }
}
