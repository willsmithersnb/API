<?php

namespace App\Http\Controllers;

use App\Models\ItemList;
use App\Transformer\ItemListTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemListController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(ItemList::class, 'item_list');
    }

    /**
     * Returns base rules for dingo validator
     *
     * @return Array rules list
     */
    private function rules()
    {
        return [
            'coupon_id' => 'sometimes|exists:App\Models\Coupon,id',
            'item_listable_type' => 'sometimes|string',
            'item_listable_id' => 'sometimes|integer',
            'gross_total' => 'sometimes|integer',
            'discount' => 'sometimes|integer',
            'discount_percentage' => 'sometimes|numeric|min:0|max:100',
            'adjustments' => 'sometimes|integer'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemLists = ItemList::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $itemLists->paginate();
            return $this->response->paginator($data, new ItemListTransformer);
        } else {
            $data = $itemLists->get();
            return $this->response->collection($data, new ItemListTransformer);
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
            $itemLists = ItemList::create($request->only(array_keys($this->rules())));
            return $this->response->item($itemLists, new ItemListTransformer);
        } catch (\Exception $ex) {
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  ItemList $itemList
     * @return \Illuminate\Http\Response
     */
    public function show(ItemList $itemList)
    {
        try {
            return $this->response->item($itemList, new ItemListTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  ItemList $itemList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemList $itemList)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new UpdateResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $itemList->update($request->only(array_keys($this->rules())));
            return $this->response->item($itemList, new ItemListTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ItemList $itemList
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemList $itemList)
    {
        try {
            $itemList->delete();
            return $this->response->item($itemList, new ItemListTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
