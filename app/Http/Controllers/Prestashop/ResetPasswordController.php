<?php

namespace App\Http\Controllers\Prestashop;

use App\Transformer\ModelTransformer;
use App\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    use Helpers;

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ];
    }

    public function submit(Request $request)
    {
        $fields = $request->only(['email', 'password', 'password_confirmation', 'token']);

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        $decoded = base64_decode($fields['token']);
        $token = json_decode($decoded);

        if ($fields['email'] == $token->email) {
            try {
                $user = User::whereEmail($fields['email'])->first();
                $user->password = bcrypt($fields['password']);
                $user->save();

                return $this->response->item($user, new ModelTransformer);
            } catch (Exception $ex) {
                throw new StoreResourceFailedException();
            }
        } else {
            throw new StoreResourceFailedException();
        }
    }
}
