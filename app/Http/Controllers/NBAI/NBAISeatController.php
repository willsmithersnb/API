<?php

namespace App\Http\Controllers\NBAI;

use App\Http\Controllers\Controller;
use App\Mail\Admin\Ai\NewSubscriber;
use App\Mail\Customer\Profile\SubscriptionPending;
use App\Mail\Customer\Profile\SubscriptionVerified;
use App\Mail\WelcomeCoupon;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Models\NBAI\Seat;
use App\Transformer\NBAISeatTransformer;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class NBAISeatController extends Controller
{
    use Helpers;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Seat::class, 'seat');
    }

    public function rules()
    {
        return [
            'payment_type' => 'required|string|in:purchase-order,stripe',
            'coupon_code' => 'sometimes|exists:coupons,coupon_code,coupon_type,nb_air'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collection = Seat::filter($request->all());
        if ($request->has('page')) {
            $data = $collection->paginate(min($request->get('perPage', 5), 15));
            return $this->response->paginator($data, new NBAISeatTransformer);
        } else {
            $data = $collection->get();
            return $this->response->collection($data, new NBAISeatTransformer);
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
            DB::beginTransaction();
            $seat = NBAISeatController::createSeat(Auth::user()->id, Auth::user()->customer_id, Auth::user()->email, $request->get('payment_type'), $request->get('coupon_code', null));
            DB::commit();
            return $this->response->item($seat, new NBAISeatTransformer);
        } catch (Exception $ex) {
            DB::rollback();
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seat $seat)
    {
        try {
            return $this->response->item($seat, new NBAISeatTransformer);
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
    public function update(Request $request, Seat $seat)
    {
        $rules = $this->rules();
        $rules['status'] = 'required|string|in:pending,active,suspended';
        $rules['user_id'] = 'sometimes|integer|unique:seats,user_id,' . $seat->id . ',user_id';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {

            if (($seat->status == "pending") && ($request->status == "active")) {
                $subscriber_verified = new SubscriptionVerified();
                Mail::to($seat->user->email)->send($subscriber_verified);
                $edu_domains = "/\.(edu|edu.ar|edu.au|edu.bd|edu.br|edu.bn|edu.cn|edu.co|edu.dj|edu.gh|edu.hk|edu.jm|edu.jo|edu.ec|edu.eg|edu.sv|edu.er|edu.et|edu.hk|edu.in|edu.lb|edu.ly|edu.my|edu.mt|edu.mx|edu.ni|edu.ng|edu.om|edu.pk|edu.pe|edu.ph|edu.pl|edu.qa|edu.sa|edu.rs|edu.sg|edu.so|edu.es|edu.sd|edu.tw|edu.tr|edu.ua|edu.uy|edu.vn)$/";
                $ac_domains = "/\.(ac|ac.at|ac.bd|ac.be|ac.cn|ac.cy|ac.fj|ac.in|ac.id|ac.ir|ac.il|ac.jp|ac.ke|ac.ma|ac.nz|ac.rw|ac.rs|ac.za|ac.kr|ac.ss|ac.lk|ac.tz|ac.th|ac.ug|ac.uk|ac.ae)$/";
                $is_academic = (preg_match($edu_domains, Auth::user()->email) || preg_match($ac_domains, Auth::user()->email));
                if (!$is_academic && config('app.coupon_code') != "") {
                    $customer_email = $seat->user->email;
                    $subscriber_coupon = new WelcomeCoupon();
                    Mail::to($customer_email)->send($subscriber_coupon);
                }
            }

            $seat->update($request->only(["expires_at", "status", "payment_type"]));

            return $this->response->item($seat, new NBAISeatTransformer);
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
    public function destroy(Seat $seat)
    {
        try {
            $seat->delete();
            return $this->response->item($seat, new NBAISeatTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }

    public static function createSeat($user_id, $customer_id, $email, $payment_type = 'purchase-order', $coupon_code = null)
    {
        $expires_at = Carbon::now();
        if (!is_null($coupon_code)) {
            $coupon_code = Coupon::where('coupon_code', strtoupper($coupon_code))->where('coupon_type', 'nb_air')->first();
            $expires_at->addDays($coupon_code->max_discount);
        } else {
            $expires_at->addYear();
        }
        $seat = new Seat();
        $seat->user_id = $user_id;
        $seat->expires_at = $expires_at;
        $seat->status = 'pending';
        $seat->payment_type = $payment_type;
        $seat->customer_id = $customer_id;
        $seat->uuid = Str::uuid();
        $seat->save();

        if (!is_null($coupon_code)) {
            $seat->status = 'active';
            CustomerCoupon::create([
                'customer_id' => $customer_id,
                'coupon_id' =>  $coupon_code->id,
                'discountable_id' =>  $seat->id,
                'discountable_type' =>  'seat',
                'redeemed_by' => $user_id,
            ]);
            $seat->save();
        }
        $fullName = User::unScoped()->find($user_id)->full_name;
        $admin_emails = config('app.admin_emails');
        $admin_mail = new NewSubscriber($fullName, $email, !is_null($coupon_code));
        Mail::to($admin_emails)->send($admin_mail);

        if (is_null($coupon_code)) {
            $subscriber_pending = new SubscriptionPending();
            $customer_email = $email;
            Mail::to($customer_email)->send($subscriber_pending);
        }
        return $seat;
    }
}
