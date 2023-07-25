<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Transformer\ItemTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(Item::class, 'item');
    }

    /**
     * Returns base rules for dingo validator
     *
     * @return Array rules list
     */
    private function rules()
    {
        return [
            'item_list_id' => 'required|exists:item_lists,id',
            'formula_id' => 'required|exists:formulas,id',
            'product_id' => 'required|exists:products,id',
            'item_summary_id' => 'required|exists:item_summaries,id',
            'item_no' => 'required|integer',
            'name' => 'required|string',
            'price' => 'required|integer',
            'cost' => 'required|integer',
            'customer_id ' => 'required|exists:customers,id'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $item = Item::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $item->paginate();
            return $this->response->paginator($data, new ItemTransformer);
        } else {
            $data = $item->get();
            return $this->response->collection($data, new ItemTransformer);
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
            $item = Item::create($request->only(array_keys($this->rules())));
            return $this->response->item($item, new ItemTransformer);
        } catch (\Exception $ex) {
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        try {
            return $this->response->item($item, new ItemTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new UpdateResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $item->update($request->only(array_keys($this->rules())));
            return $this->response->item($item, new ItemTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        try {
            $item->delete();
            return $this->response->item($item, new ItemTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
