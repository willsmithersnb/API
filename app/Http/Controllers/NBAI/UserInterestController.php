<?php

namespace App\Http\Controllers\NBAI;

use App\Http\Controllers\Controller;
use App\Models\NBAI\CellMedia;
use App\Models\NBAI\UserInterest;
use App\Transformer\UserInterestTransformer;
use App\User;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserInterestController extends Controller
{
    use Helpers;

    public function rules()
    {
        return [
            'cell_media'    => 'required|string',
            'email'       => 'required|string',
            'supplements'   => 'present|string'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userInterest = UserInterest::filter($request->all())->get();
        return $this->response->collection($userInterest, new UserInterestTransformer());
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
            $data = $request->only(['supplements', 'cell_media', 'email']);

            $cellMedia = CellMedia::firstOrCreate(['name' => $data['cell_media']]);

            $userInterest = UserInterest::create(array_merge(['cell_media_id' => $cellMedia->id], $data));

            return $this->response->item($userInterest, new UserInterestTransformer());
        } catch (\Exception $ex) {
            throw new StoreResourceFailedException;
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
            $userInterest = UserInterest::findOrFail($id);
            return $this->response->item($userInterest, new UserInterestTransformer());
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
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $userInterest = UserInterest::findOrFail($id);

            $data = $request->only(['supplements', 'cell_media', 'email']);

            $cellMedia = CellMedia::firstOrCreate(['name' => $data['cell_media']]);

            $userInterest->update(array_merge(['cell_media_id' => $cellMedia->id], $data));

            return $this->response->item($userInterest, new UserInterestTransformer());
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
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
        // Not Supported
        throw new DeleteResourceFailedException;
    }
}
