<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\FileUpload;
use App\Models\Product;
use App\Models\ProductImageUpload;
use App\Transformer\ModelTransformer;
use App\Transformer\ProductTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function rules()
    {
        return [
            'formula_id' => 'required|exists:App\Models\Formula,id',
            'product_type_id' => 'required|exists:App\Models\ProductType,id',
            'file_upload_id' => 'sometimes|nullable|exists:App\Models\FileUpload,id',
            'name' => 'required|max:191',
            'supplier_name' => 'required|max:191',
            'is_featured' => 'required|boolean',
            'is_displayed' => 'required|boolean',
            'lead_time' => 'required|integer',
            'product_description' => 'sometimes|nullable|string|max:1000',
            'is_customizable' => 'sometimes|boolean',
            'maximum_order_quantity' => 'sometimes',
            'default_order_quantity' => 'sometimes',
            'details' => 'sometimes|nullable',
            'testing_details' => 'sometimes|nullable'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $products->paginate();
            return $this->response->paginator($data, new ProductTransformer);
        } else {
            $data = $products->get();
            return $this->response->collection($data, new ProductTransformer);
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
            $product = Product::create([
                'formula_id' => $request->get('formula_id'),
                'product_type_id' => $request->get('product_type_id'),
                'name' => $request->get('name'),
                'supplier_name' => $request->get('supplier_name'),
                'is_featured' => $request->get('is_featured'),
                'is_displayed' => $request->get('is_displayed'),
                'lead_time' => $request->get('lead_time'),
                'product_description' => $request->get('product_description', null),
                'maximum_order_quantity' => $request->get('maximum_order_quantity', null),
                'default_order_quantity' => $request->get('default_order_quantity', null),
                'is_customizable' => $request->get('is_customizable')
            ]);
            if ($request->get('file_upload_id')) {
                $file = FileUpload::findOrFail($request->file_upload_id);
                $file->makePublic();
                ProductImageUpload::create(['product_id' => $product->id, 'file_upload_id' => $file->id, 'image_url' => $file->public_url]);
            }
            return $this->response->item($product, new ProductTransformer);
        } catch (Exception $ex) {
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try {
            return $this->response->item($product, new ProductTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            $product = Product::findOrFail($product->id);
            $product->formula_id = $request->get('formula_id');
            $product->product_type_id = $request->get('product_type_id');
            $product->name = $request->get('name');
            $product->supplier_name = $request->get('supplier_name');
            $product->is_featured = $request->get('is_featured');
            $product->is_displayed = $request->get('is_displayed');
            $product->lead_time = $request->get('lead_time');
            $product->product_description = $request->get('product_description', null);
            $product->testing_details = is_null($request->get('testing_details')) ? null : json_encode($request->get('testing_details'));
            $product->maximum_order_quantity = $request->get('maximum_order_quantity', null);
            $product->default_order_quantity = $request->get('default_order_quantity', null);
            $product->is_customizable = $request->get('is_customizable');
            $product->save();
            if ($request->get('file_upload_id')) {
                ProductImageUpload::where('product_id', $product->id)->delete();
                $file = FileUpload::findOrFail($request->file_upload_id);
                $file->makePublic();
                ProductImageUpload::create(['product_id' => $product->id, 'file_upload_id' => $file->id, 'image_url' => $file->public_url]);
            }

            return $this->response->item($product, new ProductTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (Exception $ex) {
            throw new UpdateResourceFailedException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return $this->response->item($product, new ProductTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
