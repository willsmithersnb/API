<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\FileUpload;
use App\Models\Order;
use App\Models\Pod;
use App\Transformer\PodTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PodController extends ResourceController
{
    protected $model_class = Pod::class;

    protected $url_key = 'pod';

    public function __construct()
    {
        $this->authorizeResource(Pod::class, 'pod');
    }

    protected function rules()
    {
        return [
            'pods.*.pod_serial' => 'required|unique:pods,pod_serial,NULL,id,deleted_at,NULL',
            'order_id' => 'required|integer|exists:App\Models\Order,id',
        ];
    }

    protected function transformer()
    {
        return new PodTransformer;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());;
        }
        try {
            DB::beginTransaction();
            $order_id = $request->get('order_id');
            $pod_serial_data = $request->get('pods');
            $items = Order::unScoped()->findOrFail($order_id)->itemList()->unScoped()->first()->items()->unScoped()->get();
            $item_packaging_options = $items[0]->itemPackagingOptions()->unScoped()->get();
            foreach ($item_packaging_options as $item_packaging_option) {
                $item_packaging_option_id = $item_packaging_option->id;
                $customer_id = $item_packaging_option->customer_id;
            }
            foreach ($pod_serial_data as $pod_serials) {
                $pod_serials = collect($pod_serials);
                $pod = new Pod();
                $pod->pod_serial = $pod_serials->get('pod_serial');
                $pod->order_id = $order_id;
                $pod->item_packaging_option_id = $item_packaging_option_id;
                $pod->customer_id = $customer_id;
                $response = Http::patch(config('app.stoic_url') . '/api/device_managements/link_by_serial_no/' . $pod_serials->get('pod_serial') . '?format=json', ['order_id' => $order_id, 'origin' => config('app.lux_url'),]);
                if ($response->successful()) {
                    $pod->save();
                } else {
                    return response()->json(['code' => 404, 'message' => 'The pod serial number provided is either already linked to another order or does not exist.'], 404);
                }
            }
            DB::commit();
            return response()->json(['code' => 200, 'message' => 'Success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new StoreResourceFailedException($th);
        }
    }

    public function show(Pod $pod)
    {
        return parent::showObject($pod);
    }

    public function update(Request $request, Pod $pod)
    {
        return parent::updateObject($request, $pod);
    }

    public function destroy(Pod $pod)
    {
        $response = Http::patch(config('app.stoic_url') . '/api/device_managements/unlink_by_serial_no/' . $pod->pod_serial . '?format=json', ['order_id' => $pod->order_id, 'origin' => config('app.lux_url'),]);
        if ($response->successful()) {
            return parent::destroyObject($pod);
        } else {
            return response()->json(['code' => 404, 'message' => 'Failed'], 404);
        }
    }

    public function podQrDownloadLink(Request $request)
    {
        $qr_generation_type = $request->get('qr_generation_type');
        $extension = $request->get('extension');
        $file_name = 'QR_PDF_'.$qr_generation_type;
        if($qr_generation_type == 'pod'){
            $pod_id = $request->get('pod_id');
            $pod = Pod::findOrFail($pod_id);
            $order = Order::unScoped()->findOrFail($pod->order_id);
            $file_name.='_PodID#'.$pod->pod_serial;
            $linking =  Http::post(config('app.stoic_url') . '/api/device_managements/qr-code/?format=json', ['order_name' => $order->name, 'pod_serial' => $pod->pod_serial, 'is_pod' => $request->get('is_pod'), 'origin' => config('app.lux_url')]);
        }else{
            $order_id = $request->get('id');
            $order = Order::unScoped()->findOrFail($order_id);
            $file_name.='_OrderID#'.$order_id;
            $linking =  Http::post(config('app.stoic_url') . '/api/device_managements/qr-code/?format=json', ['order_name' => $order->name, 'order_id' => $order->id, 'is_pod' => $request->get('is_pod'), 'origin' => config('app.lux_url'), 'bulk_generate' => $qr_generation_type]);
        }
        return response($linking->body(),200,[
            'Content-Type' => 'application/' . $extension,
            'Content-disposition' => 'inline; filename="' . $file_name . '.' . $extension . '"'
        ]);
    }

    public function uploadPodRunActivityLog(Request $request)
    {
        if ((config('app.received_key')) === ($request->get('STOIC_ACCESS_KEY'))) {
            $pod = Pod::where('pod_serial', $request->get('pod_serial'))->first();
            if (!is_null($pod)) {
                $order = Order::unScoped()->findOrFail($pod->order_id);
                $response = HTTP::get($request->get('url'));
                $pdf = $response->getBody();
                $generation_time =  time();
                $name = 'Tech Docs-Pod Run Activity Report ' . $request->get('pod_serial');
                $file_name =  "_" . $name . "_" . $generation_time . '.pdf';
                $path = config('app.env') . "/temp/document/pod_runs/".$generation_time;
                Storage::disk('s3')->put($path . $file_name, $pdf);
                $uuid = $uuid = Str::uuid();
                $file_upload = FileUpload::create(['bucket_path' => $path, 'uuid' => $uuid, 'extension' => 'pdf', 'file_name' => $file_name]);
                Documentation::create(['name' => $name, 'order_id' => $order->id, 'file_upload_id' => $file_upload->id, 'customer_id' => $order->customer_id]);
                return response()->json(['code' => 200, 'message' => 'Success']);
            }
        } else {
            return response()->json(['code' => 401], 401);
        }
    }
}
