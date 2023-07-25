<?php

namespace App\Http\Controllers;

use App\Mail\Customer\Orders\CustomerOrderTransfer;
use App\Models\Address;
use App\Models\CustomerCoupon;
use App\Models\Order;
use App\Models\PackagingOption;
use App\Models\Quote;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ItemListableTransferController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();
            $item_listable_id = $request->get('item_listable_id');
            $item_listable_type = $request->get('item_listable_type');
            $customer_id = $request->get('customer_id');
            $user_id = $request->get('user_id');
            $user = User::unScoped()->findOrFail($user_id);
            $customer_addresses = Address::unScoped()->where('customer_id', $customer_id)->get();
            $item = ($item_listable_type === 'order') ? Order::unScoped()->findOrFail($item_listable_id) : Quote::unScoped()->findOrFail($item_listable_id);
            $product_type = $item->itemList()->unScoped()->first()->items()->unScoped()->first()->productType()->first();
            if (($product_type->name === 'Specialized Media') || ($product_type->name === 'Supplements and Reagents')) {
                $is_specialized = true;
            } else {
                $is_specialized = false;
            }
            $item_shipping_address = $item->shippingAddress()->unScoped()->first();
            $item_billing_address = $item->billingAddress()->unScoped()->first();

            if($customer_addresses->isEmpty()) {
                if($item_shipping_address->id === $item_billing_address->id) {
                    $shipping_address_id = $billing_address_id = $this->createAddress($customer_id, $item_shipping_address);
                } else {
                    $shipping_address_id = $this->createAddress($customer_id, $item_shipping_address);
                    $billing_address_id = $this->createAddress($customer_id, $item_billing_address);
                }
            } else {
                if($item_shipping_address->id === $item_billing_address->id) {
                    $shipping_address_id = $billing_address_id = $this->findOrCreateAddress($customer_addresses, $item_shipping_address, $customer_id);
                } else {
                    $shipping_address_id = $this->findOrCreateAddress($customer_addresses, $item_shipping_address, $customer_id);
                    $billing_address_id = $this->findOrCreateAddress($customer_addresses, $item_billing_address, $customer_id);
                }
            }

            $customer_coupons = CustomerCoupon::where(['discountable_id' => $item_listable_id, 'discountable_type' => $item_listable_type])->get();
            foreach ($customer_coupons as $customer_coupon) {
                $customer_coupon->customer_id = $customer_id;
                $customer_coupon->save();
            }

            $item->customer_id = $customer_id;
            $item->user_id = $user_id;
            $item->shipping_address_id = $shipping_address_id;
            $item->billing_address_id =  $billing_address_id;
            $item->save();

            $this->transferItemList($request, $item);

            // customer order placement mail
            $customerNoticeMail = new CustomerOrderTransfer(
                $item_listable_id,
                $item->name,
                $request->get('orderDetails')['company_name'],
                $request->get('orderDetails')['total'],
                $item->name,
                $request->get('orderDetails')['format'],
                number_format((float)$request->get('orderDetails')['formulation_weight'], 2, '.', ''),
                number_format((float)$request->get('orderDetails')['predicted_osmolality'], 2, '.', ''),
                $request->get('orderDetails')['cgmp'],
                $request->get('orderDetails')['total_liters'],
                $request->get('orderDetails')['lead_time'],
                $is_specialized
            );

            Mail::to($user->email)->send($customerNoticeMail);

            DB::commit();
            return response()->json([
                'message' => $item_listable_type . ' has been transferred successfully',
                'data' => $item
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    private function createAddress($customer_id, $address_data)
    {
        $address = new Address();
        $address->customer_id = $customer_id;
        $address->line_1 = $address_data->line_1;
        $address->line_2 = $address_data->line_2;
        $address->city = $address_data->city;
        $address->state = $address_data->state;
        $address->zip_code = $address_data->zip_code;
        $address->country = $address_data->country;
        $address->archived_at = null;
        $address->object_hash = $address_data->object_hash;
        $address->save();
        return $address->id;
    }

    private function findOrCreateAddress($customer_addresses, $address_data, $customer_id)
    {
        foreach ($customer_addresses as $customer_address) {
            if ($customer_address->object_hash == $address_data->object_hash) {
                return $customer_address->id;
            }
        }
        return $this->createAddress($customer_id, $address_data);
    }

    private function transferItemList(Request $request, $item_listable)
    {
        try {
            DB::beginTransaction();

            // Item List Table Update
            $item_listable->itemList()->unScoped()->first()->update(['customer_id' => $request->get('customer_id')]);

            // Item Table Update
            $item_listable->itemList()->unScoped()->first()->items()->unScoped()->update(['customer_id' => $request->get('customer_id')]);

            // Formula Table Update
            $item_listable->itemList()->unScoped()->first()->items()->unScoped()->first()->formula()->unScoped()->update(['customer_id' => $request->get('customer_id')]);

            // Formula Ingredient Table Update
            $item_listable->itemList()->unScoped()->first()->items()->unScoped()->first()->formula()->unScoped()->first()->formulaIngredients()->unScoped()->update(['customer_id' => $request->get('customer_id')]);

            // Formula Ingredient Material Table Update
            $formula_ingredients = $item_listable->itemList()->unScoped()->first()->items()->unScoped()->first()->formula()->unScoped()->first()->formulaIngredients()->unScoped();
            foreach ($formula_ingredients as $formula_ingredient) {
                $formula_ingredient->formulaIngredientMaterials()->unScoped()->update(['customer_id' => $request->get('customer_id')]);
            }

            // Custom Ingredient Table Update
            $item_listable->itemList()->unScoped()->first()->items()->unScoped()->first()->formula()->unScoped()->first()->customIngredients()->unScoped()->update(['customer_id' => $request->get('customer_id')]);

            // Item Summary Table Update
            $item_listable->itemList()->unScoped()->first()->items()->unScoped()->first()->itemSummary()->unScoped()->update(['customer_id' => $request->get('customer_id')]);

            //Item Packaging Options Table Update
            $item_packaging_options = $item_listable->itemList()->unScoped()->first()->items()->unScoped()->first()->itemPackagingOptions()->get();

            foreach ($item_packaging_options as $item_packaging_option) {
                $existing_packaging_option = PackagingOption::unScoped()->withTrashed()->find($item_packaging_option->packaging_option_id);
                $packaging_option = PackagingOption::unScoped()
                    ->where([
                        ['customer_id', null],
                        ['object_hash', $existing_packaging_option->object_hash]
                    ])->orWhere([
                        ['customer_id', $request->get('customer_id')],
                        ['object_hash', $existing_packaging_option->object_hash]
                    ])->first();
                if (is_null($packaging_option)) {
                    $packaging_option = new PackagingOption();
                    $packaging_option->fill($existing_packaging_option->toArray());
                    $packaging_option->customer_id = $request->get('customer_id');
                    $packaging_option->save();

                    // Packaging Option Materials Table Update
                    $materials = $existing_packaging_option->materials()->get();
                    $materials->each(function ($material) use ($packaging_option) {
                        $material->materialable_id = $packaging_option->id;
                        $material->save();
                    });
                }

                $item_packaging_option->packaging_option_id = $packaging_option->id;
                $item_packaging_option->customer_id = $request->get('customer_id');
                $item_packaging_option->save();
            }


            //Item QcTest Methods Table Update
            $item_listable->itemList()->unScoped()->first()->items()->unScoped()->first()->itemQcTestMethod()->update(['customer_id' => $request->get('customer_id')]);

            // Document Table Update
            $item_listable->documents()->unScoped()->update(['customer_id' => $request->get('customer_id')]);

            // Item Pricing Rules Table Update
            $item_listable->itemList()->unScoped()->first()->items()->unScoped()->first()->itemPricingRule()->unScoped()->update(['customer_id' => $request->get('customer_id')]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception);
        }
    }
}
