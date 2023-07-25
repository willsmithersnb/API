<?php

namespace App\Http\Controllers;

use App\Helper\UnitHelper;
use App\Mail\Admin\Orders\AdminNewOrderNotice;
use App\Mail\Admin\Quotes\AdminQuotation;
use App\Mail\Customer\Orders\CustomerOrderConfirmation;
use App\Mail\Customer\Quotes\CustomerQuotation;
use App\User;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CustomerCoupon;
use App\Models\CustomIngredient;
use App\Models\Formula;
use App\Models\FormulaIngredient;
use App\Models\Ingredient;
use App\Models\Item;
use App\Models\ItemList;
use App\Models\ItemPackagingOption;
use App\Models\ItemPricingAddonTier;
use App\Models\ItemQcTestMethod;
use App\Models\ItemSummary;
use App\Models\PricingAddon;
use App\Models\PricingAddonTier;
use App\Models\Material;
use App\Models\Order;
use App\Models\PackagingOption;
use App\Models\ProductImageUpload;
use App\Models\ProductType;
use App\Models\QcTestMethod;
use App\Models\Quote;
use App\Transformer\OrderTransformer;
use Carbon\Carbon;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PlaceItemListable extends Controller
{
    use Helpers;

    protected $customer_id = null;
    protected $user_id = null;
    protected $ingredients = null;
    protected $qc_test_methods = null;
    protected $packaging_options = null;
    protected $itemable_class;
    protected $productTypes;

    public function __construct()
    {
        $this->ingredients  = Ingredient::all();
        $this->materials  = Material::all();
        $this->qc_test_methods = QcTestMethod::all();
        $this->packaging_options = PackagingOption::all();
        $this->pricing_addon = PricingAddon::all();
        $this->pricing_addon_tier = PricingAddonTier::all();
        $this->product_type = ProductType::all();
        $this->productTypes = ["Specialized Media", "Supplements and Reagents"];
    }

    protected function itemable()
    {
        return new $this->itemable_class();
    }

    protected function rules()
    {
        return [
            'name' => 'required',
            'formula_ingredients.*.ingredient_id' => 'required|integer|exists:App\Models\Ingredient,id',
            'formula_ingredients.*.materials' => 'sometimes',
            'formula_ingredients.*.quantity_unit' => 'required|integer|digits_between:0,6',
            'formula_ingredients.*.quantity' =>  'required|integer|digits_between:0,18',
            'formula_ingredients.*.unit_type' => 'required|integer',
            'formula_ingredients.*.materials.*' => 'sometimes|integer|exists:App\Models\Material,id',
            'qc_tests.*.id' => 'sometimes|integer|exists:App\Models\QcTest,id',
            'qc_tests.*.qcTestMethod' => 'sometimes|nullable|integer|exists:App\Models\QcTestMethod,id'
        ];
    }

    protected function ruleMessages()
    {
        return [
            'formula_ingredients.*.ingredient_id' => 'No Ingredient was found with this id',
            'formula_ingredients.*.materials.*' => 'No Material was found with this id'
        ];
    }

    private function storeFormula(Request $request)
    {
        // Create formula
        $formula = Formula::create([
            'name' => $request->get('name'),
            'formula_hash' => 'DEMOHASH',
            'customer_id' => $this->customer_id
        ]);

        // Create formula_ingredients;
        $formula_ingredients_data = $request->get('formula_ingredients', []);
        foreach ($formula_ingredients_data as $formula_ingredient_data) {

            $ingredient = $this->ingredients->firstOrFail('id', $formula_ingredient_data['ingredient_id']);
            $price = $ingredient->price;
            $cost = $ingredient->cost;
            if ($request->get('isAdjusted')) {
                $price = $formula_ingredient_data['price'];
                $cost =  $formula_ingredient_data['cost'];
            }
            //TODO:Calculate Price
            $quantity_unit =  $formula_ingredient_data['quantity_unit'];
            $quantity = $formula_ingredient_data['quantity'];
            $pricing_unit = $ingredient->pricing_unit;
            $unit_type = $ingredient->unit_type;

            $formula_ingredient = FormulaIngredient::create([
                'ingredient_id' => $ingredient->id,
                'formula_id' => $formula->id,
                'quantity_unit' => $quantity_unit,
                'quantity' => $quantity,
                'price' => $price,
                'cost' => $cost,
                'pricing_unit' => $pricing_unit,
                'unit_type' => $unit_type,
                'customer_id' => $this->customer_id
            ]);
            if (array_key_exists('materials', $formula_ingredient_data)) {
                foreach ($formula_ingredient_data['materials'] as $material_id) {
                    $material = $this->materials->firstOrFail('id', $material_id);
                    $formula_ingredient->formulaIngredientMaterials()->create(
                        [
                            'material_id' => $material->id,
                            'formula_ingredient_id' => $formula_ingredient->id,
                            'pricing_unit' => $material->pricing_unit,
                            'cost' => $material->cost,
                            'price' => $material->price,
                            'unit_type' => $material->unit_type,
                            'customer_id' => $this->customer_id
                        ]
                    );
                }
            }
        }
        return $formula;
    }

    private function storeTestingOptions(Request $request, Item $item)
    {
        $qc_tests_data = $request->get('qc_tests', []);
        foreach ($qc_tests_data  as $qc_test) {
            $qc_test = collect($qc_test);
            try {
                $qc_test_method_id = $qc_test->get('qcTestMethod');
                $price = 0;
                $cost = 0;
                if (!is_null($qc_test_method_id)) {
                    $qc_test_method = $this->qc_test_methods->firstOrFail('id', $qc_test_method_id);
                    $price = $qc_test_method->price;
                    $cost = $qc_test_method->cost;
                    if ($request->get('isAdjusted')) {
                        $price = $qc_test->get('price');
                        $cost = $qc_test->get('cost');
                    }
                }
                ItemQcTestMethod::create([
                    'item_id' => $item->id,
                    'qc_test_id' => $qc_test->get('id'),
                    'qc_test_method_id' => $qc_test_method_id,
                    'price' => $price,
                    'cost' =>  $cost,
                    'value' => json_encode($qc_test->get('value', null)),
                    'customer_id' => $this->customer_id
                ]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    private function storePackagingOptions(Request $request, Item $item)
    {
        $packaging_options_data = $request->get('packaging_options', []);
        foreach ($packaging_options_data  as $item_packaging_options) {
            $item_packaging_options = collect($item_packaging_options);
            try {
                $packaging_option = $this->packaging_options->firstOrFail('id', $item_packaging_options->get('id'));
                $price = $packaging_option->price;
                $cost = $packaging_option->cost;
                if ($request->get('isAdjusted')) {
                    $price = $item_packaging_options->get('price');
                    $cost = $item_packaging_options->get('cost');
                }
                ItemPackagingOption::create([
                    'item_id' => $item->id,
                    'packaging_option_id' => $packaging_option->id,
                    'price' => $price,
                    'cost' => $cost,
                    'fill_amount' => $item_packaging_options->get('fill_volume'),
                    'fill_unit' => $packaging_option->fill_unit,
                    'unit_type' => $packaging_option->unit_type,
                    'quantity' => $item_packaging_options->get('quantity'),
                    'value' => json_encode($item_packaging_options->get('configuration', null)),
                    'max_fill_volume' => $packaging_option->max_fill_volume,
                    'fill_tolerance' => $packaging_option->fill_tolerance,
                    'customer_id' => $this->customer_id
                ]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    private function storeItemPricingAddonTiers(Request $request, Item $item)
    {
        $item_pricing_add_tiers_data = $request->get('pricingAddons', []);
        foreach ($item_pricing_add_tiers_data as $item_pricing_addon_tiers) {
            $item_pricing_addon_tiers = collect($item_pricing_addon_tiers);
            try {
                $pricing_addon = $this->pricing_addon->firstOrFail('id', $item_pricing_addon_tiers->get('id'));
                $pricing_addon_tier = $this->pricing_addon_tier->firstOrFail('id', $item_pricing_addon_tiers->get('pricing_add_on_tier'));
                $price = $pricing_addon_tier->price;
                $cost = $pricing_addon_tier->cost;
                if ($request->get('isAdjusted')) {
                    $price = $item_pricing_addon_tiers->get('price');
                    $cost = $item_pricing_addon_tiers->get('cost');
                }
                ItemPricingAddonTier::create([
                    'item_id' => $item->id,
                    'pricing_addon_id' => $pricing_addon->id,
                    'pricing_addon_tier_id' => $pricing_addon_tier->id,
                    'conditional_variable' => $pricing_addon->conditional_variable,
                    'name' => $pricing_addon->name,
                    'pricing_type' => $pricing_addon->pricing_type,
                    'cost_type' => $pricing_addon->cost_type,
                    'is_customer_visible' => $pricing_addon->is_customer_visible,
                    'is_enabled' => $pricing_addon->is_enabled,
                    'condition_greater_than' => $pricing_addon_tier->condition_greater_than,
                    'price' => $price,
                    'cost' => $cost
                ]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    private function storeCustomComponents(Request $request, $formula_id)
    {
        $custom_components = $request->get('custom_components', []);
        foreach ($custom_components as $custom_component) {
            CustomIngredient::create([
                'name' => $custom_component['name'],
                'manufacturer' => optional($custom_component)['manufacturer'],
                'catalog_no' => optional($custom_component)['catalog_no'],
                'quantity' => $custom_component['quantity'],
                'quantity_unit' => $custom_component['display_unit'],
                'unit_type' => $custom_component['unit_type'],
                'cas_no' => $custom_component['cas_no'],
                'customer_id' =>  $this->customer_id,
                'formula_id' => $formula_id
            ]);
        }
    }

    private function storeCustomerCoupons(Request $request, $item_listable_type, $item_listable_id)
    {
        CustomerCoupon::create([
            'customer_id' => $this->customer_id,
            'coupon_id' => $request->get('coupon_id'),
            'discountable_id' => $item_listable_id,
            'discountable_type' => $item_listable_type,
            'redeemed_by' => $this->user_id
        ]);
    }

    private function storeItemSummary(Request $request)
    {
        $product_type = $this->product_type->firstOrFail('name', ($request->product_type ?? 'Base Media'));
        $product_id = $request->get('product_id', null);
        return $item_summary = ItemSummary::create([
            'format' => $request->get('format', 'Liquid'),
            'concentration' => $request->get('concentration', null),
            'pH' => $request->get('pH', null),
            'cgmp_manufacturing' => $request->get('cgmp_manufacturing', 'No') == 'No' ? false : true,
            'formulation_weight'  => $request->get('total_formulation_weight', null),
            'predicted_osmolality' => $request->get('predicted_osmolality', null),
            'customer_id' => $this->customer_id,
            'product_type_id' => $product_type->id,
            'product_id' => $product_id,
            'notes' => $request->get('notes')
        ]);
    }

    private function storeItemListable(Request $request, $item_listable_type, $item_listable_id, $product_image_url)
    {
        // Store Formula
        $formula = $this->storeFormula($request);
        if (!is_null($request->get('coupon_id'))) {
            $coupon = Coupon::get()->firstOrFail('id', $request->get('coupon_id'));
            if ($item_listable_type == 'order') {
                $this->storeCustomerCoupons($request, $item_listable_type, $item_listable_id);
            }
        }

        $item_list = ItemList::create([
            'item_listable_type' => $item_listable_type,
            'item_listable_id' => $item_listable_id,
            'gross_total' => (int)($request->get('total_price', 0) * 100),
            'discount' => $request->get('discount', 0),
            'discount_percentage' => ($coupon->discount_percentage ?? 0),
            'customer_id' => $this->customer_id,
            'image_url' => $product_image_url,
            'coupon_id' => $request->get('coupon_id', null)
        ]);

        //Store Item Summary
        $item_summary = $this->storeItemSummary($request);

        // Store Item
        $product_type = $this->product_type->firstOrFail('name', ($request->product_type ?? 'Base Media'));
        $product_id = $request->get('product_id', null);
        $item = Item::create([
            'item_list_id' => $item_list->id,
            'formula_id' => $formula->id,
            'product_id' => $product_id,
            'product_type_id' => $product_type->id,
            'item_summary_id' => $item_summary->id,
            'item_no' => 1,
            'name' => $request->get('name'),
            'price' => 0,
            'cost' => 0,
            'note' => $request->get('note'),
            'customer_id' => $this->customer_id,
            'product_option_id' => $request->get('product_option_id', null)
        ]);

        // Store Testing Options
        $this->storeTestingOptions($request, $item);

        // Store packaging Options
        $this->storePackagingOptions($request, $item);

        // Store Item Pricing Addon Tiers
        $this->storeItemPricingAddonTiers($request, $item);

        // Store Custom Ingredients
        if ($item_listable_type == 'quote' || $item_listable_type == 'cart') {
            $this->storeCustomComponents($request, $formula->id);
        }
    }

    public function placeOrder(Request $request)
    {
        Log::info('order.requests', ['request' => $request->all()]);

        $this->user_id = Auth::user()->id;
        $this->customer_id = Auth::user()->customer_id;

        $user = Auth::user()->getFullNameAttribute();
        $userEmail = Auth::user()->email;
        $customerName = Auth::user()->customer->name;
        $isSpecialized = in_array($request->get('product_type'), $this->productTypes);

        //fetching product image url
        $product_image_url = ProductImageUpload::where('product_id', $request->get('product_id'))->value('image_url');
        $this->itemable_class = Order::class;

        $packaging_options_data = $request->get('packaging_options', []);
        foreach ($packaging_options_data  as $item_packaging_options) {
            $item_packaging_options = collect($item_packaging_options);
            $packaging_option = PackagingOption::get()->firstOrFail('id', $item_packaging_options->get('id'));
            if ($packaging_option->packaging_type == 'Pod') {
                $product_image_url = null;
            }
        }

        $validator = Validator::make($request->all(), $this->rules(), $this->ruleMessages());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try {
            DB::beginTransaction();
            $itemable_record = $this->itemable()::create([
                'customer_id' => $this->customer_id,
                'name' => $request->get('name'),
                'user_id' => $this->user_id,
                'delivery_date' => Carbon::now()->addWeeks($request->get('estimated_lead_time', 9)),
                'billing_address_id' => $request->get('billing_address')['id'],
                'shipping_address_id' => $request->get('shipping_address')['id'],
                'image_url' => $product_image_url
            ]);

            $net_total = $request->get('total_price', 0) - ($request->get('discount', 0) / 100);

            $this->storeItemListable($request, \Str::lower(class_basename($this->itemable())), $itemable_record->id, $product_image_url);
            DB::commit();

            if ($packaging_option->packaging_type == 'Pod') {
                $user_details = User::get()->firstOrFail('id', $this->user_id);
                $customer = Customer::withTrashed()->get()->firstOrFail('id', $this->customer_id);
                $user_details->makeVisible('password');

                try {
                    foreach (config('app.put_request_urls') as $url) {
                        $response = Http::post($url, ['request' => $request->all(), 'user' => $user_details, 'account' => $customer, 'order_id' => $itemable_record->id, 'origin' => config('app.lux_url')]);
                        Log::info('stoic api request', ['stoicsss' => $response]);
                    }
                } catch (\Throwable $e) {
                }
            }

            // admin notice mail

            $adminEmailSendTo = config('app.admin_emails');
            $adminNoticeMail = new AdminNewOrderNotice(
                $itemable_record->id,
                $request->get('name'),
                $user,
                $customerName,
                number_format((float)$net_total, 2, '.', ''),
                $request->get('name'),
                $request->get('format'),
                number_format((float)$request->get('total_formulation_weight'), 2, '.', ''),
                number_format((float)$request->get('predicted_osmolality'), 2, '.', ''),
                $request->get('cgmp_manufacturing'),
                $request->get('total_order_size'),
                $request->get('estimated_lead_time'),
                $request->get('notes')
            );

            Mail::to($adminEmailSendTo)->send($adminNoticeMail);

            // customer order placement mail

            $customerNoticeMail = new CustomerOrderConfirmation(
                $itemable_record->id,
                $request->get('name'),
                $user,
                $customerName,
                number_format((float)$net_total, 2, '.', ''),
                $request->get('name'),
                $request->get('format'),
                number_format((float)$request->get('total_formulation_weight'), 2, '.', ''),
                number_format((float)$request->get('predicted_osmolality'), 2, '.', ''),
                $request->get('cgmp_manufacturing'),
                $request->get('total_order_size'),
                $request->get('estimated_lead_time'),
                $request->get('notes'),
                $itemable_record->billingAddress,
                $itemable_record->shippingAddress,
                $request->get('isPricingHidden'),
                $isSpecialized
            );

            Mail::to($userEmail)->send($customerNoticeMail);

            return $this->response->item($itemable_record, new OrderTransformer);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new StoreResourceFailedException($th);
        }
    }

    public function placeCart(Request $request)
    {
        Log::info('cart.requests', ['request' => $request->all()]);

        $this->user_id = Auth::user()->id;
        $this->customer_id = Auth::user()->customer_id;

        //fetching product image url
        $product_image_url = ProductImageUpload::where('product_id', $request->get('product_id'))->value('image_url');
        $this->itemable_class = Cart::class;

        $validator = Validator::make($request->all(), $this->rules(), $this->ruleMessages());

        $packaging_options_data = $request->get('packaging_options', []);
        foreach ($packaging_options_data  as $item_packaging_options) {
            $item_packaging_options = collect($item_packaging_options);
            $packaging_option = PackagingOption::get()->firstOrFail('id', $item_packaging_options->get('id'));
            if ($packaging_option->packaging_type == 'Pod') {
                $product_image_url = null;
            }
        }

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try {
            DB::beginTransaction();
            $itemable_record = $this->itemable()::create([
                'customer_id' => $this->customer_id,
                'custom_components' => json_encode($request->get('custom_components', [])),
                'name' => $request->get('name'),
                'user_id' => $this->user_id,
                'image_url' => $product_image_url
            ]);
            $this->storeItemListable($request, \Str::lower(class_basename($this->itemable())), $itemable_record->id, $product_image_url);
            DB::commit();
            return $this->response->item($itemable_record, new OrderTransformer);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new StoreResourceFailedException($th);
        }
    }

    public function placeQuote(Request $request)
    {
        Log::info('quote.requests', ['request' => $request->all()]);
        $user = Auth::user();
        $adminEmailSendTo = config('app.admin_emails');
        $this->user_id = Auth::user()->id;
        $this->customer_id = Auth::user()->customer_id;
        $customerName = Auth::user()->customer->name;

        //fetching product image url
        $product_image_url = ProductImageUpload::where('product_id', $request->get('product_id'))->value('image_url');
        $this->itemable_class = Quote::class;

        $validator = Validator::make($request->all(), $this->rules(), $this->ruleMessages());

        $packaging_options_data = $request->get('packaging_options', []);
        foreach ($packaging_options_data  as $item_packaging_options) {
            $item_packaging_options = collect($item_packaging_options);
            $packaging_option = PackagingOption::get()->firstOrFail('id', $item_packaging_options->get('id'));
            if ($packaging_option->packaging_type == 'Pod') {
                $product_image_url = null;
            }
        }

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try {
            DB::beginTransaction();
            $itemable_record = $this->itemable()::create([
                'customer_id' => $this->customer_id,
                'custom_components' => json_encode($request->get('custom_components', [])),
                'name' => $request->get('name'),
                'user_id' => $this->user_id,
                'billing_address_id' => optional($request->get('billing_address'))['id'],
                'shipping_address_id' => optional($request->get('shipping_address'))['id'],
                'image_url' => $product_image_url
            ]);

            $this->storeItemListable($request, \Str::lower(class_basename($this->itemable())), $itemable_record->id, $product_image_url);
            DB::commit();

            //send admin new quote request

            $adminQuoteNoticeMail = new AdminQuotation(
                $itemable_record->id,
                $request->get('name'),
                $user->getFullNameAttribute(),
                $customerName,
                number_format((float)$request->get('total_price'), 2, '.', ''),
                $request->get('name'),
                $request->get('format'),
                number_format((float)$request->get('total_formulation_weight'), 2, '.', ''),
                number_format((float)$request->get('predicted_osmolality'), 2, '.', ''),
                $request->get('cgmp_manufacturing'),
                $request->get('total_order_size'),
                $request->get('estimated_lead_time'),
                $request->get('notes'),
                $itemable_record->billingAddress,
                $itemable_record->shippingAddress
            );

            Mail::to($adminEmailSendTo)->send($adminQuoteNoticeMail);

            $customerQuoteNoticeMail = new CustomerQuotation(
                $itemable_record->id,
                $request->get('name'),
                $user->getFullNameAttribute(),
                $customerName,
                number_format((float)$request->get('total_price'), 2, '.', ''),
                $request->get('name'),
                $request->get('format'),
                number_format((float)$request->get('total_formulation_weight'), 2, '.', ''),
                number_format((float)$request->get('predicted_osmolality'), 2, '.', ''),
                $request->get('cgmp_manufacturing'),
                $request->get('total_order_size'),
                $request->get('estimated_lead_time'),
                $request->get('notes'),
                $itemable_record->billingAddress,
                $itemable_record->shippingAddress,
            );

            Mail::to($user->email)->send($customerQuoteNoticeMail);
            return $this->response->item($itemable_record, new OrderTransformer);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new StoreResourceFailedException($th);
        }
    }
}
