<?php

namespace App\Http\Controllers;

use App\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    use Helpers;
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        $credentials = $validator->validated();
        $credentials['email'] = strtolower($credentials['email']);
        if (!$token = Auth::attempt($credentials)) {
            throw new UnauthorizedHttpException(
                get_class($this),
                'The email address and/or password that you\'ve entered doesn\'t match. Please check your credentials.'
            );
        }
        activity()->log('Logged In');
        return $this->createNewToken($token);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        activity()->log('Logged Out');
        return response()->json(['message' => 'You\'ve successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(Auth::refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        // Guide for transformed API's
        $user = auth()->user()->toArray();
        $user['customer'] = auth()->user()->customer;
        $user['roles'] = auth()->user()->roles;
        return $this->response->array($user);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email:rfc'
        ]);

        if ($validator->fails()) {
            throw new UpdateResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try{
            $email = strtolower($request->get('email'));

            $user = User::unScoped()->where('email', $email)->firstOrFail();
            $status = Password::sendResetLink(
                ['email' => $user->email],
                function ($user, $token) {
                    $user->sendPasswordResetNotification($token);
                }
            );
            return response()->json(['message' => __($status)]);
        }catch(\Exception $ex){

        }
    }

    public function saveNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new UpdateResourceFailedException('Missing Required Fields', $validator->errors());
        }
        $fields = $request->only('email', 'password', 'password_confirmation', 'token');
        $fields['email'] = strtolower($fields['email']);
        $status = Password::reset(
            $fields,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return response()->json(['message' => __($status)]);
    }

    public function verifyUser(Request $request)
    {
        try {

            $user = User::unScoped()->findOrFail($request->get('user_id'));

            if (!hash_equals((string) $request->get('hash'), sha1($user->getEmailForVerification()))) {
                throw new AuthorizationException;
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }
            return response()->json(['message' => 'User email verified']);
        } catch (\Exception $ex) {
            throw new AuthorizationException;
        }
    }
}
