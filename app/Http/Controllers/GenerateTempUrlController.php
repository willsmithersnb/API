<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class GenerateTempUrlController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function rules()
    {
        return [
            'doc_type' => 'required|string|in:order-spec_sheet,quote-spec_sheet,order-cost_breakdown,quote-cost_breakdown,order-qr_generation,order-all_qr_generation'
        ];
    }

    public function __invoke(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            abort(404);
        } else {
            $parameters = explode("-", $request->get('doc_type'));
            $document_name = $parameters[0];
            $document_type = $parameters[1];
            $route = '';
            $options =   [
                'id' => $id,
                'type' => $document_type,
                'doc_name' => $document_name
            ];
            if (isAdminOriginated()) {
                switch ($document_type) {
                    case ('cost_breakdown'):
                        $route = strtolower($document_name) == 'quote' ? 'quote.cost-breakdown' : 'orders.cost-breakdown';
                        break;
                    case ('spec_sheet'):
                        $route = 'specification-sheet';
                        $options['format'] = 'docx';
                        break;
                    case('qr_generation'):
                        $route = 'qr-code';
                        $options['qr_generation_type'] = 'pod';
                        $options['pod_id'] = $request->get('pod_id');
                        $options['is_pod'] = $request->get('is_pod');
                        $options['extension'] = $request->get('extension');
                        break;
                    case('all_qr_generation'):
                        $route = 'qr-code';
                        $options['qr_generation_type'] = 'order';
                        $options['is_pod'] = $request->get('is_pod');
                        $options['extension'] = $request->get('extension');
                        break;
                }
            } else {
                $route = 'specification-sheet';
                $options['format'] = 'pdf';
            }
        }
        $response = URL::temporarySignedRoute(
            $route,
            now()->addMinutes(config('app.temp_url_ttl', 1)),
            $options
        );
        return response()->json(['data' => ['url' => $response]], 200);
    }
}
