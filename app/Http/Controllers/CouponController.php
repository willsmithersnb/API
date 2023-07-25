<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Transformer\ModelTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Rules\UpperCase;
use App\Transformer\CouponTransformer;

class CouponController extends Controller
{
    use Helpers;

    private function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'max_discount' => 'sometimes|numeric',
            'discount_percentage' => 'sometimes|numeric',
            'expires_at' => 'required|date',
            'min_amount' => 'sometimes|integer',
            'coupon_type' => 'required|string|in:nb_air,nb_lux',
            'coupon_code' => 'required|alpha_num',
            'valid_from' => 'required|date',
            'max_redemptions' => 'required|integer|min:0',
            'limit_redemption_by' => 'required|in:users,customers,customer_coupons',
            'customer_id' => 'sometimes|integer|nullable',
            'valid_period' => 'sometimes|integer|nullable',
            'description' => 'sometimes'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $coupons = Coupon::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $coupons->paginate();
            return $this->response->paginator($data, new ModelTransformer);
        }
        $data = $coupons->get();
        return $this->response->collection($data, new ModelTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = $this->rules();
        $rules['coupon_code'] .= '|unique:coupons,coupon_code';
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $coupon = Coupon::create($request->only(array_keys($this->rules())));
            return $this->response->item($coupon, new CouponTransformer);
        } catch (Exception $ex) {
            throw new StoreResourceFailedException($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            return $this->response->item($coupon, new CouponTransformer);
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
    public function update(Request $request, $id)
    {
        $rules = $this->rules();
        $rules['coupon_code'] .= '|unique:coupons,coupon_code,' . $id;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->update($request->only(array_keys($rules)));
            return $this->response->item($coupon, new ModelTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();
            return $this->response->item($coupon, new ModelTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }

    public function verify(Coupon $coupon)
    {
        $customer_coupons = $coupon->customersCoupons()->count();
        if (($customer_coupons >= $coupon->max_redemptions)){
            return response()->json(['data' => ['status_code' => 400, 'message' => 'Sorry! The redemption limit for this coupon has been reached. Please try another coupon code.']], 400);
        }
        return $this->response->item($coupon, new ModelTransformer);
    }
}
