<?php

namespace App\Http\Controllers\NBAI;

use App\Http\Controllers\Controller;
use App\Models\NBAI\Recommendation;
use App\Transformer\RecommendationsTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RecommendationsController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(Recommendation::class, 'recommendation');
    }

    public function rules()
    {
        return [
            'cell_type_id' => 'required|integer|exists:cell_types,id',
            'ingredient_id' => 'required|integer|exists:ingredients,id',
            'deviation' => 'required|in:increase,decrease,maintain',
            'concentration_high' => 'required|integer|min:0',
            'concentration_mid' => 'required|integer|min:0',
            'concentration_low' => 'required|integer|min:0',
            'concentration_unit' => 'required|integer',
            'score'             => 'required|numeric|min:0.001|max:100',
            'quote'             => 'required',
            'research_paper_id' => 'required|integer|exists:research_papers,id'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recommendations = Recommendation::filter($request->all());
        if (Auth::hasUser() && Auth::check()) {
            return $this->response->paginator($recommendations->paginate(min($request->get('perPage', 5), 15)), new RecommendationsTransformer());
        } else {
            return $this->response->collection($recommendations->get(), new RecommendationsTransformer());
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
            $data = $request->only(['cell_type_id', 'ingredient_id', 'deviation', 'concentration_high', 'concentration_mid', 'concentration_low', 'concentration_unit', 'score', 'quote', 'research_paper_id']);

            $data['score'] = round($data['score'], 3);

            $recommendations = Recommendation::create($data);

            return $this->response->item($recommendations, new RecommendationsTransformer());
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
    public function show(Recommendation $recommendation)
    {
        try {
            return $this->response->item($recommendation, new RecommendationsTransformer());
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
    public function update(Request $request, Recommendation $recommendation)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $data = $request->only(['cell_type_id', 'ingredient_id', 'deviation', 'concentration_high', 'concentration_mid', 'concentration_low', 'concentration_unit', 'score', 'quote', 'research_paper_id']);
            $data['score'] = round($data['score'], 3);
            $recommendation->update($data);

            return $this->response->item($recommendation, new RecommendationsTransformer());
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
    public function destroy(Recommendation $recommendation)
    {
        try {
            $recommendation->delete();
            return $this->response->item($recommendation, new RecommendationsTransformer());
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
