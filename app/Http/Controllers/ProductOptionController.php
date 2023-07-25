<?php

namespace App\Http\Controllers;

use App\Models\ProductOption;
use App\Models\ProductPackagingOption;
use App\Models\ProductQcTest;
use App\Transformer\ProductOptionTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductOptionController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(ProductOption::class, 'product_option');
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:App\Models\Product,id',
            'packaging_options.*.packaging_option_id' => 'required|exists:App\Models\PackagingOption,id',
            'name' => 'required|string|max:191',
            'qc_tests.*.qc_test_id' => 'required|exists:App\Models\QcTest,id',
            'fill_volume' => 'required|integer',
            'price' => 'required|integer|digits_between:0,18',
            'cost' => 'required|integer|digits_between:0,18',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productOption = ProductOption::filter($request->all());

        // Activates Pagination if sent
        if ($request->has('page')) {
            $data = $productOption->paginate();
            return $this->response->paginator($data, new ProductOptionTransformer);
        }
        $data = $productOption->get();
        return $this->response->collection($data, new ProductOptionTransformer);
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
            DB::beginTransaction();
            $productOption = ProductOption::create([
                'name' => $request->get('name'),
                'product_id' => $request->get('product_id'),
                'fill_volume' => $request->get('fill_volume'),
                'price' => $request->get('price'),
                'cost' => $request->get('cost')
            ]);
            $productOption->save();

            $productPackagingOptionData = $request->get('product_packaging_options', []);
            $packagingOptionData = [];
            foreach ($productPackagingOptionData as $productPackagingOptions) {
                $productPackagingOptions = collect($productPackagingOptions);

                $productPackagingOption = new ProductPackagingOption;
                $productPackagingOption->product_option_id = $productOption->id;
                $productPackagingOption->packaging_option_id = $productPackagingOptions->get('id');
                $productPackagingOption->value = json_encode($productPackagingOptions->get('configuration', null));

                array_push($packagingOptionData, $productPackagingOption);
            }
            $productOption->productPackagingOptions()->saveMany($packagingOptionData);

            $productQcTestsData = $request->get('product_qc_tests', []);
            $qcTestData = [];
            foreach ($productQcTestsData as $productQcTests) {
                $productQcTests = collect($productQcTests);

                $productQcTest = new ProductQcTest;
                $productQcTest->product_option_id = $productOption->id;
                $productQcTest->qc_test_id = $productQcTests->get('id');
                $productQcTest->qc_test_method_id = $productQcTests->get('qc_test_method_id');
                $productQcTest->value = json_encode($productQcTests->get('value', null));

                array_push($qcTestData, $productQcTest);
            }
            $productOption->productQcTests()->saveMany($qcTestData);
            DB::commit();
            return $this->response->item($productOption, new ProductOptionTransformer);
        } catch (\Throwable $ex) {
            DB::rollBack();
            throw new StoreResourceFailedException($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductOption $productOption)
    {
        try {
            return $this->response->item($productOption, new ProductOptionTransformer);
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
    public function update(Request $request, ProductOption $productOption)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            DB::beginTransaction();
            $productOption->name = $request->get('name');
            $productOption->fill_volume = $request->get('fill_volume');
            $productOption->price = $request->get('price');
            $productOption->cost = $request->get('cost');
            $productOption->save();

            $productOption->productPackagingOptions()->delete();
            $productPackagingOptionData = $request->get('product_packaging_options', []);
            $productPackagingOptionsInstances = [];

            foreach ($productPackagingOptionData as $productPackagingOptions) {
                $productPackagingOption = new ProductPackagingOption;
                $productPackagingOption->product_option_id = $productOption->id;
                $productPackagingOption->packaging_option_id = data_get($productPackagingOptions, 'id');
                $productPackagingOption->value = json_encode(data_get($productPackagingOptions, 'configuration', null));

                array_push($productPackagingOptionsInstances, $productPackagingOption);
            }

            $productOption->productPackagingOptions()->saveMany($productPackagingOptionsInstances);

            $productOption->productQcTests()->delete();
            $productQcTestsData = $request->get('product_qc_tests', []);
            $qcTestData = [];

            foreach ($productQcTestsData as $productQcTests) {
                $productQcTest = new ProductQcTest;
                $productQcTest->product_option_id = $productOption->id;
                $productQcTest->qc_test_id = data_get($productQcTests, 'id');
                $productQcTest->qc_test_method_id = data_get($productQcTests, 'qc_test_method_id');
                $productQcTest->value = json_encode(data_get($productQcTests, 'value', null));

                array_push($qcTestData, $productQcTest);
            }
            $productOption->productQcTests()->saveMany($qcTestData);
            DB::commit();
            return $this->response->item($productOption, new ProductOptionTransformer);
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
    public function destroy(ProductOption $productOption)
    {
        try {
            $productOption->productPackagingOptions()->delete();
            $productOption->productQcTests()->delete();
            $productOption->delete();
            return $this->response->item($productOption, new ProductOptionTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (\Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
