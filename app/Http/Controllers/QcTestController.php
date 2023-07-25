<?php

namespace App\Http\Controllers;

use App\Models\QcTest;
use App\Policies\QcTestPolicy;
use App\Transformer\QcTestTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QcTestController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(QcTest::class, 'qc_test');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'ui_component_name' => 'required|string|max:191',
            'description' => 'nullable|string|max:191',
            'has_custom_value' => 'required|boolean',
            'basal_enabled' => 'required|boolean',
            'balanced_salt_enabled' => 'required|boolean',
            'buffer_enabled' => 'required|boolean',
            'cryo_enabled' => 'required|boolean'
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $qcTests = QcTest::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $qcTests->paginate();
            return $this->response->paginator($data, new QcTestTransformer);
        } else {
            $data = $qcTests->get();
            return $this->response->collection($data, new QcTestTransformer);
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
            $qcTest = QcTest::create($request->only(array_keys($this->rules())));
            return $this->response->item($qcTest, new QcTestTransformer);
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
    public function show(QcTest $qcTest)
    {
        try {
            return $this->response->item($qcTest, new QcTestTransformer);
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
    public function update(Request $request, QcTest $qcTest)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $qcTest->update($request->only(array_keys($this->rules())));
            return $this->response->item($qcTest, new QcTestTransformer);
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
    public function destroy(QcTest $qcTest)
    {
        try {
            $qcTest->delete();
            return $this->response->item($qcTest, new QcTestTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
