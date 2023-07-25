<?php

namespace App\Http\Controllers\NBAI;

use App\Http\Controllers\Controller;
use App\Models\NBAI\NbRecommendations;
use App\Transformer\ModelTransformer;
use App\Transformer\NbRecommendationsTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NbRecommendationsController extends Controller
{
    use Helpers;

    public function rules()
    {
        return [
            'cell_type_id' => 'required|int|exists:cell_types,id',
            'formula_name' => 'required|string|max:190',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nbRecommendations = NbRecommendations::filter($request->all())->get();
        return $this->response->collection($nbRecommendations, new NbRecommendationsTransformer());
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
            $nbRecommendations = NbRecommendations::create($request->only(['cell_type_id', 'formula_name']));

            return $this->response->item($nbRecommendations, new NbRecommendationsTransformer());
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
            $nbRecommendations = NbRecommendations::findOrFail($id);
            return $this->response->item($nbRecommendations, new NbRecommendationsTransformer());
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
            $nbRecommendations = NbRecommendations::findOrFail($id);
            $nbRecommendations->update($request->only(['cell_type_id', 'formula_name']));
            return $this->response->item($nbRecommendations, new NbRecommendationsTransformer());
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
        try {
            $nbRecommendations = NbRecommendations::findOrFail($id);
            $nbRecommendations->delete();
            return $this->response->item($nbRecommendations, new NbRecommendationsTransformer());
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
