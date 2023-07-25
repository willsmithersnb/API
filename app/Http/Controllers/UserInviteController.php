<?php

namespace App\Http\Controllers;

use App\Mail\Customer\Profile\UserInviteEmail;
use App\Models\UserInvite;
use App\Transformer\UserInviteTransformer;
use Carbon\Carbon;
use Dingo\Api\Exception\StoreResourceFailedException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UserInviteController extends ResourceController
{
    protected $model_class = UserInvite::class;

    protected $url_key = 'user_invite';

    protected $rule_set = [
        'email' => 'required|email|unique:users,email',
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'permissions' => 'sometimes|array',
        'permissions.*' => ''
    ];

    protected function transformer()
    {
        return new UserInviteTransformer;
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
        $rules['permissions.*'] .= 'required|in:' . implode(",", config('enums.customer_permissions')) . '';
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try {
            $invited_by = Auth::user();
            $sendTo = $request->get('email');
            $first_name = $request->get('first_name');
            $last_name = $request->get('last_name');

            $expires = Carbon::now()->addMinutes(config('app.presigned_url_expiry_time'));

            DB::beginTransaction();
            UserInvite::where('email', $sendTo)->delete();
            $user_invite = UserInvite::create([
                'email' => $sendTo,
                'invited_by' => $invited_by->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'expires_at' => $expires,
                'customer_id' => $invited_by->customer_id,
                'permissions' => json_encode($request->get('permissions') ?? [])
            ]);
            $user_invite->save();
            DB::commit();
            $url = parse_url(URL::signedRoute('users.store', [], $expires, true));
            $signature = $url['query'];
            $get_started_url = route('user-invite', ['email' => $sendTo, 'first_name' => $first_name, 'last_name' => $last_name, 'company_name' => $invited_by->company_name]) . '&' . $signature;
            $mail = new UserInviteEmail($user_invite->getFullNameAttribute(), $request->get('email'), $get_started_url, $invited_by->getFullNameAttribute(), $invited_by->company_name);
            Mail::to($sendTo)->send($mail);
            return $this->response->item($user_invite, new UserInviteTransformer);
        } catch (Exception $ex) {
            DB::rollBack();
            throw new StoreResourceFailedException;
        }
    }

    public function show(UserInvite $user_invite)
    {
        return parent::showObject($user_invite);
    }

    public function update(Request $request, $id)
    {
        //Not implemented
    }

    public function destroy($id)
    {
        //Not Implemented
    }
}
