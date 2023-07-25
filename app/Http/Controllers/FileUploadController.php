<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Models\FileUploadLog;
use App\Transformer\ModelTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileUploadController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(FileUpload::class, 'file_upload');
    }

    private function rules()
    {
        return [
            'prefix' => 'required|in:firmware,document,product',
            'extension' => 'required|string|max:50',
            'file_name' => 'sometimes|string|max:150'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $file_upload = FileUpload::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $file_upload->paginate();
            return $this->response->paginator($data, new ModelTransformer);
        } else {
            $data = $file_upload->get();
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
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $extension = $request->extension;
            $prefix = $request->prefix;
            $file_origin_name = $request->has('file_name') ? $request->get('file_name') : "";
            $path = config('app.env') . "/{$prefix}/";
            $expires_in = config('app.presigned_url_expiry_time');

            $uuid = Str::uuid();
            $adaptor = Storage::disk('s3')->getAdapter();
            $client = $adaptor->getClient();
            $bucket = $adaptor->getBucket();
            $file_name = $uuid . '__' . $file_origin_name;

            $command = $client->getCommand(
                'PutObject',
                [
                    'Bucket' => $bucket,
                    'Key' => $path . $file_name
                ]
            );

            $upload_request = $client->createPresignedRequest($command, "+{$expires_in} minutes");
            $presignedUrl = (string)$upload_request->getUri();

            FileUploadLog::create([
                'user_id' => Auth::hasUser() && Auth::check() ? auth()->user()->id : null,
                'ip_address' =>  $request->ip(),
                'bucket_path' => $path,
                'uuid' => $uuid,
                'extension' => $extension,
                'file_name' => $file_origin_name
            ]);

            $file_upload = FileUpload::create(['bucket_path' => $path, 'uuid' => $uuid, 'extension' => $extension, 'file_name' => $file_name]);
            $file_upload->presigned_url = $presignedUrl;
            $file_upload->expires_in = "{$expires_in} minutes";
            $file_upload->makeVisible(['expires_in', 'presigned_url']);

            return $this->response->item($file_upload, new ModelTransformer);
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
    public function show(FileUpload $file_upload)
    {
        try {
            return $this->response->item($file_upload, new ModelTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundException;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FileUpload $file_upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileUpload $file_upload)
    {
        //
    }

    public function download_file(FileUpload $file_upload)
    {
        $file_upload->download_url = $file_upload->getFileSourceUrlAttribute();
        $file_upload->makeVisible(['download_url']);
        return $this->response->item($file_upload, new ModelTransformer);
    }
}
