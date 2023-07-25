<?php

namespace App\Http\Controllers\Prestashop;

use App\Http\Controllers\Controller;
use App\Transformer\ModelTransformer;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailVerificationController extends Controller
{
	use Helpers;

	public function rules()
	{
		return [
			'email' => 'required|email|exists:users,email'
		];
	}

	public function submit(Request $request)
	{
		$email = $request->get('email');

		$validator = Validator::make($request->all(), $this->rules());

		if ($validator->fails()) {
			throw new UpdateResourceFailedException('Missing Required Fields', $validator->errors());
		}

		try {
			$user = User::where('email', $email)->first();

			if ($user->email_verified_at !== null) {
				throw new UpdateResourceFailedException("Email is already verified.");
			}

			$user->email_verified_at = Carbon::now();
			$user->save();
			return $this->response->item($user, new ModelTransformer());
		} catch (Exception $ex) {
			throw new UpdateResourceFailedException();
		}
	}
}
