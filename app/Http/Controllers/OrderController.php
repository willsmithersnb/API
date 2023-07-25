<?php

namespace App\Http\Controllers;

use App\Mail\Customer\Orders\ChangeLogCustomer;
use App\Models\Favorite;
use App\Models\ItemList;
use App\Models\ItemListChangeLog;
use App\Models\Order;
use App\Transformer\OrderTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class OrderController extends ResourceController
{
    protected $model_class = Order::class;

    protected $url_key = 'order';

    protected $rule_set = [
        'name' => 'required|string|max:191',
        'customer_id' => 'required|exists:App\Models\Customer,id',
        'order_status_id' => 'sometimes|integer',
        'payment_status_id' => 'sometimes|integer',
        'payment_type_id' => 'sometimes|integer',
        'order_signal' => 'sometimes|in:on_track,needs_action,delayed',
        'is_automatic' => 'sometimes|boolean',
        'description' => 'sometimes',
    ];

    protected function transformer()
    {
        return new OrderTransformer;
    }

    public function store(Request $request)
    {
        $this->except->add('customer_id');
        return parent::storeObject(requestBodyWithCustomerID($request));
    }

    public function show(Order $order)
    {
        if (($order->is_automatic === TRUE) && (now() > $order->delivery_date)) {
            $order->order_signal = 'delayed';
            $order->save();
        }
        return parent::showObject($order);
    }

    public function update(Request $request, Order $order)
    {
        $this->except->add('customer_id');
        $order->delivery_date = $request->get('delivery_date');

        switch ($request->get('change_type')) {
            case ('order_status'):
                $previous_status = config('enums.order_statuses')[$order->order_status_id];
                $status_name = config('enums.order_statuses')[$request->get('order_status_id')];
                break;
            case ('payment_status'):
                $previous_status = config('enums.payment_statuses')[$order->payment_status_id];
                $status_name = config('enums.payment_statuses')[$request->get('payment_status_id')];
                break;
            case ('payment_type'):
                $previous_status = config('enums.payment_types')[$order->payment_type_id];
                $status_name = config('enums.payment_types')[$request->get('payment_type_id')];
                break;
            case ('lead_time'):
                $previous_status = null;
                $status_name = 'Lead Time Updated';
                break;
        }
        if(isAdminOriginated()){
            $item_list = ItemList::where('item_listable_id', $order->id)->first();
            $item_list_change_log = new ItemListChangeLog();
            $item_list_change_log->change_type = $request->get('change_type');
            $item_list_change_log->status_name = $status_name;
            $item_list_change_log->user_id = Auth::user()->id;
            $item_list_change_log->item_list_id = $item_list->id;
            $item_list_change_log->description = $request->get('description');
            $item_list_change_log->is_visible_to_customer = $request->get('is_visible_to_customer');
            $item_list_change_log->is_email_triggered = $request->get('is_email_triggered');
            $item_list_change_log->save();

            if ($request->get('is_email_triggered') === TRUE) {
                $userEmail = User::unScoped()->where('id', $order->user_id)->first()->email;
                $customerNotification = new ChangeLogCustomer(
                    $order->id,
                    str_replace('_', ' ', $item_list_change_log->change_type),
                    $order->name,
                    str_replace('_', ' ', ucwords($status_name)),
                    str_replace('_', ' ',ucwords($previous_status))
                );
                Mail::to($userEmail)->send($customerNotification);
            }
        }
        return parent::updateObject($request, $order);
    }

    public function destroy(Order $order)
    {
        $favorite = Favorite::where('favoriteable_id', $order->id)
            ->where('favoriteable_type', \Str::lower(class_basename($this->model_class)));
        $favorite->delete();
        return parent::destroyObject($order);
    }
}
