<?php

namespace App\Http\Controllers;

use App\Mail\Customer\Orders\ChangeLogCustomer;
use App\Models\ItemList;
use App\Models\ItemListChangeLog;
use App\Models\Order;
use App\Transformer\ItemListChangeLogTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ItemListChangeLogController extends ResourceController
{
    protected $model_class = ItemListChangeLog::class;

    protected $url_key = 'item_list_change_log';

    protected $rule_set = [
        'change_type' =>  'required|string|in:order_status,payment_status,payment_type,manual,lead_time',
        'status_name' => 'required|string',
        'user_id' => 'required|integer|exists:App\User,id',
        'item_list_id' => 'required|integer|exists:App\Models\ItemList,id',
        'description' => 'required',
        'is_visible_to_customer' => 'required|boolean',
        'is_email_triggered' => 'required|boolean'
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
        return new ItemListChangeLogTransformer;
    }

    public function store(Request $request)
    {
        $itemList = ItemList::where('item_listable_id', $request->get('order_id'))->first();
        $order = Order::findOrFail($itemList->item_listable_id);
        $request->merge(['item_list_id' => $itemList->id, 'user_id' => Auth::user()->id]);
        if ($request->get('is_email_triggered') === TRUE) {
            $userEmail = User::unScoped()->where('id', $order->user_id)->first()->email;
            $previous_status = null;
            $customerNotification = new ChangeLogCustomer(
                $order->id,
                $request->get('change_type'),
                $order->name,
                $request->get('status_name'),
                $previous_status
            );
            Mail::to($userEmail)->send($customerNotification);
        }
        return parent::storeObject($request);
    }

    public function show(int $id)
    {
        $itemListChangeLog = ItemListChangeLog::findOrFail($id);
        return parent::showObject($itemListChangeLog);
    }

    public function update(int $id, Request $request)
    {
        $itemListChangeLog = ItemListChangeLog::findOrFail($id);
        return parent::updateObject($request, $itemListChangeLog);
    }

    public function destroy(ItemListChangeLog $itemListChangeLog)
    {
        return parent::destroyObject($itemListChangeLog);
    }
}
