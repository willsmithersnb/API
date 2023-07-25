<?php

namespace App\Http\Controllers;

use App\Models\QcTestMethod;
use App\Transformer\QcTestMethodTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QcTestMethodController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(QcTestMethod::class, 'qc_test_method');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'price' => 'required|numeric',
            'cost' => 'required|integer',
            'qc_test_id' => 'required|exists:App\Models\QcTest,id',
            'enabled' => 'required|boolean'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $qcTests = QcTestMethod::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $qcTests->paginate();
            return $this->response->paginator($data, new QcTestMethodTransformer);
        } else {
            $data = $qcTests->get();
            return $this->response->collection($data, new QcTestMethodTransformer);
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
            $qcTestMethod = QcTestMethod::create($request->only(array_keys($this->rules())));
            return $this->response->item($qcTestMethod, new QcTestMethodTransformer);
        } catch (\Exception $ex) {
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QcTestMethod  $qcTestMethod
     * @return \Illuminate\Http\Response
     */
    public function show(QcTestMethod $qcTestMethod)
    {
        try {
            return $this->response->item($qcTestMethod, new QcTestMethodTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QcTestMethod  $qcTestMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QcTestMethod $qcTestMethod)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $qcTestMethod->update($request->only(array_keys($this->rules())));
            return $this->response->item($qcTestMethod, new QcTestMethodTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QcTestMethod  $qcTestMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(QcTestMethod $qcTestMethod)
    {
        try {
            $qcTestMethod->delete();
            return $this->response->item($qcTestMethod, new QcTestMethodTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
