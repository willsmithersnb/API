<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ManageSubAccountPermissionController extends Controller
{
    private function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'roles' => 'sometimes|array',
            'roles.*' => 'sometimes|in:' . implode(",", config('enums.customer_permissions')) . ''
        ];
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (!Auth::user()->hasRole("Manage Connected Customers Users")) {
            throw new Exception("Unauthorized attempt");
        }

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try {
            if (!in_array("Manage Connected Customers Users", $request->get('roles')) && Auth::user()->id == $request->get('user_id')) {
                throw new Exception("Cannot Remove Role");
            }

            $user = User::findOrFail($request->get('user_id'));
            $user->syncRoles($request->get('roles'));
            return response()->json(['roles' => $request->get('roles')]);
        } catch (Exception $ex) {
            throw new Exception($ex);
        }
    }
}
