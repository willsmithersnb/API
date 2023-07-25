<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Policies\DevicePolicy;
use App\Transformer\ModelTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeviceController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(Device::class, 'device');
    }

    private function rules()
    {
        return [
            'model_name' => 'required|string|max:100',
            'device_name' => 'required|string|max:100',
            'model_no' => 'required|string|max:100',
            'manufacture_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'hardware_version' => 'required|string|max:100',
            'last_known_ip' => 'required|ip',
            'last_seen' => 'required|date',
            'command' => 'json'
        ];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $device = Device::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $device->paginate();
            return $this->response->paginator($data, new ModelTransformer);
        } else {
            $data = $device->get();
            return $this->response->collection($data, new ModelTransformer);
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
        $rules = array_merge($this->rules(), array('ssh_public_key' => 'required|string', 'password' => 'required|string|min:6'));
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $device = Device::create(array_merge($request->only(array_keys($rules)), ['uuid' => Str::uuid(), 'created_by' => Auth::user()->id]));

            return $this->response->item($device, new ModelTransformer);
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
    public function show(Device $device)
    {
        try {
            return $this->response->item($device, new ModelTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundException();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Device $device)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new UpdateResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $device->update($request->only(array_keys($this->rules())));
            return $this->response->item($device, new ModelTransformer);
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
    public function destroy(Device $device)
    {
        try {
            $device->delete();
            return $this->response->item($device, new ModelTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException();
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException();
        }
    }
}
