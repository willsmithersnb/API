<?php

namespace App\Http\Controllers;

use App\Http\Controllers\NBAI\NBAISeatController;
use App\Http\Requests\ChangePasswordRequest;
use App\Mail\Customer\Profile\NewSubscriber;
use App\Mail\Customer\Profile\ProfileUpdated;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\UserInvite;
use App\Transformer\UserTransformer;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    private function rules()
    {
        return [
            'email' => 'required|email|max:191',
            'password' => 'required|string|min:6|confirmed',
            'company_name' => 'nullable|string|max:150',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'department' => 'required|string|max:150',
            'field_of_work' => 'nullable|string|max:191',
            'job_title' => 'nullable|string|max:191',
            'cell_type_interests' => 'nullable|array',
            'cell_type_interests.*' => 'string|max:191|distinct',
            'is_corporate' => 'required|boolean',
            'customer_id' => 'sometimes|nullable|exists:customers,id',
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
        $users = User::filter($request->all())->paginate();
        return $this->response->withPaginator($users, new UserTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->only([
            'email', 'password', 'password_confirmation', 'company_name', 'first_name', 'last_name', 'department', 'field_of_work', 'job_title', 'cell_type_interests', 'is_corporate', 'customer_id'
        ]);

        $rules = $this->rules();
        $rules['email'] .= '|unique:users,email';

        $validator = Validator::make($fields, $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try {
            DB::beginTransaction();
            $company_name = $fields['is_corporate'] ? $fields['company_name'] : $fields['first_name'] . ' ' . $fields['last_name'];
            $permissions = [];
            $signature = $request->get('signature', '');
            if ($request->has('signature') && $signature != '' && $signature != 'undefined') {
                if ($request->hasValidSignature()) {
                    $invite = UserInvite::unScoped()->where('email', $fields['email'])->latest('id')->first();
                    $invited_by = $invite->invitedBy()->unScoped()->first();
                    $invite->accepted_at = Carbon::now();
                    $invite->save();
                    $permissions = json_decode($invite->permissions, true);
                    $company_name = $invited_by->company_name;
                    $customer = $invited_by->customer()->unScoped()->first();
                } else {
                    throw new StoreResourceFailedException;
                }
            } else {
                $permissions = config('enums.customer_permissions');
                if ($fields['is_corporate']) {
                    $company_name = $fields['company_name'];
                    $permissions[] = 'corporate-customer';
                } else {
                    $company_name = $fields['first_name'] . ' ' . $fields['last_name'];
                    $permissions[] = 'individual-customer';
                }
                $customer = $this->getCustomerObjectForUser($fields['is_corporate'], $company_name, $fields['customer_id'] ?? null);
            }

            $fields['password']        = bcrypt($fields['password']);
            $fields['customer_id']     = $customer->id;
            $fields['profile_picture'] = "To Be Completed";
            $fields['cell_type_interests'] = json_encode($fields['cell_type_interests'] ?? []);
            $fields['email'] = strtolower($fields['email']);

            $user = User::create($fields);
            $user->syncRoles($permissions);

            if ($request->get('ai_subscription', false)) {
                NBAISeatController::createSeat($user->id, $customer->id, $user->email, 'purchase-order', $request->get('coupon_code', null));
            }
            $user->sendEmailVerificationNotification();
            DB::commit();
            return $this->response->item($user, new UserTransformer);
        } catch (Exception $ex) {
            DB::rollback();
            throw new StoreResourceFailedException($ex);
        }
    }

    private function getCustomerObjectForUser(bool $isCorporate, string $name, int $customerId = null): Customer
    {
        if ($customerId != null) {
            return Customer::find($customerId);
        }

        return Customer::create([
            'name' => $name,
            'customer_type' => $isCorporate ? 'corporate' : 'individual'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        try {
            return $this->response->item($user, new UserTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = $this->rules();
        $rules['email'] .= '|unique:users,email,' . $user->id;
        $rules['password'] = 'sometimes';
        $rules['is_corporate'] = 'sometimes';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try {
            $fields = $request->only(["first_name", "last_name", "department", "field_of_work", "job_title", "cell_type_interests", "profile_picture", "email"]);
            $fields['profile_picture'] = "To Be Completed";
            $fields['email'] = strtolower($fields['email']);
            $fields['cell_type_interests'] = json_encode($fields['cell_type_interests'] ?? []);
            $user->update($fields);
            return $this->response->item($user, new UserTransformer);
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
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return $this->response->item($user, new UserTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }

    public function change_password(ChangePasswordRequest $changePasswordRequest)
    {
        try {
            $user = auth()->user();
            $user->password = bcrypt($changePasswordRequest['new_password']);
            $user->save();
            return $this->response->item($user, new UserTransformer);
        } catch (Exception $ex) {
            throw new StoreResourceFailedException();
        }
    }
}
