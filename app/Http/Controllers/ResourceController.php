<?php

namespace App\Http\Controllers;

use App\Transformer\ModelTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ResourceController extends Controller
{
    use Helpers;

    protected $rule_set = [];

    protected $except;

    protected $model_class;

    protected $url_key;


    public function __construct()
    {
        $this->except = collect([]);
        $this->authorizeResource($this->model_class, $this->url_key);
    }


    /**
     * Returns a controllers model
     * 
     * @return Illuminate\Database\Eloquent\Model
     */

    protected function model()
    {
        return new $this->model_class();
    }
    /**
     * Returns an array of request attributes rules
     * 
     */
    protected function rules()
    {
        return collect($this->rule_set)->except($this->except)->toArray();
    }


    /**
     * Returns the Model Transformer 
     * 
     * @return App\Transformer\ModelTransformer
     */
    protected abstract function transformer();


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collection = $this->model()::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $collection->paginate(min($request->get('perPage', 5), 15));
            return $this->response->paginator($data, $this->transformer());
        } else {
            $data = $collection->get();
            return $this->response->collection($data, $this->transformer());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeObject(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try {
            $model = $this->model()::create($request->only(array_keys($this->rules())));
            return $this->response->item($model, $this->transformer());
        } catch (\Exception $ex) {
            throw new StoreResourceFailedException($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showObject(Model $model)
    {
        try {
            return $this->response->item($model, $this->transformer());
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Model $model
     * @return \Illuminate\Http\Response
     */
    public function updateObject(Request $request, Model $model)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $model->update($request->only(array_keys($this->rules())));
            return $this->response->item($model, $this->transformer());
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Model $model
     * @return \Illuminate\Http\Response
     */
    public function destroyObject(Model $model)
    {
        try {
            $model->delete();
            return $this->response->item($model, $this->transformer());
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
