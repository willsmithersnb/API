<?php

namespace App\Http\Controllers;

use App\Mail\Admin\Invites\AdminInvite;
use App\Transformer\ModelTransformer;
use App\Transformer\UserTransformer;
use App\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use Exception;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends UserController
{
    public function rules()
    {
        return [
            'email' => 'required|string|exists:users,email',
        ];
    }

    public function __construct()
    {
        $this->middleware(['role:admin|super-admin']);
    }

    public function index(Request $request)
    {
        $request->merge(['roleName' => ['super-admin', 'admin']]);
        return parent::index($request);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return parent::show($user);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules());
            if ($validator->fails()) {
                throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
            }
            $role = 'admin';
            $user = User::where('email', $request->get('email'))->first();
            if ($user->hasRole($role)) {
                return ['message' => 'This user already has admin privileges', 'status_code' => 304];
            } else {
                $user->assignRole([$role]);
                $mail = new AdminInvite();
                Mail::to($user->email)->send($mail);
                return $this->response->item($user, new UserTransformer);
            }
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundException();
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $role = 'admin';
        if ($user->hasRole($role)) {
            $user->removeRole($role);
            return $this->response->item($user, new UserTransformer);
        } else {
            return ['message' => 'This user does not have admin privileges', 'status_code' => 304];
        }
    }
}
