<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['middleware' => 'bindings'], function ($api) {
        // Auth Routes
        $api->group(['prefix' => 'auth'], function ($api) {
            $api->post('/login', 'App\Http\Controllers\AuthController@login');
            $api->post('/refresh', 'App\Http\Controllers\AuthController@refresh');

            $api->group(['middleware' => 'jwt.auth'], function ($api) {
                $api->post('/logout', 'App\Http\Controllers\AuthController@logout');
                $api->get('/user', 'App\Http\Controllers\AuthController@userProfile');
            });
        });

        $api->resource('users', 'App\Http\Controllers\UserController', [
            'only' => ['store']
        ]);

        $api->resource('recommendations', 'App\Http\Controllers\NBAI\RecommendationsController', [
            'only' => ['index']
        ]);
        $api->get('aggregate-score-by/{column}', 'App\Http\Controllers\NBAI\NBAIChartController@aggregateScoreBy');

        $api->resource('ingredients', 'App\Http\Controllers\IngredientController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('products', 'App\Http\Controllers\ProductController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('product-types', 'App\Http\Controllers\ProductTypeController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('materials', 'App\Http\Controllers\MaterialController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('qc-test-methods', 'App\Http\Controllers\QcTestMethodController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('qc-tests', 'App\Http\Controllers\QcTestController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('packaging-options', 'App\Http\Controllers\PackagingOptionController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('ingredient-types', 'App\Http\Controllers\IngredientTypeController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('pricing-rules', 'App\Http\Controllers\PricingRuleController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('pricing-addons', 'App\Http\Controllers\PricingAddonController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('pricing-addon-tiers', 'App\Http\Controllers\PricingAddonTierController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('formulas', 'App\Http\Controllers\FormulaController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('catalogs', 'App\Http\Controllers\CatalogController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('recommended-products', 'App\Http\Controllers\RecommendedProductController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('product-options', 'App\Http\Controllers\ProductOptionController', [
            'only' => ['index', 'show']
        ]);
        $api->resource('product-documentations', 'App\Http\Controllers\ProductDocumentController', [
            'only' => ['index', 'show']
        ]);

        $api->get('coupon/verify/{coupon:coupon_code}', 'App\Http\Controllers\CouponController@verify');

        $api->put('materials/bulk-upload', 'App\Http\Controllers\IGMPMaterialsController');

        $api->group(['middleware' => 'jwt.auth'], function ($api) {
            $api->resource('users', 'App\Http\Controllers\UserController', [
                'except' => ['store']
            ]);
            $api->put('sync-permissions', 'App\Http\Controllers\User\ManageSubAccountPermissionController');
            $api->resource('user-invite', 'App\Http\Controllers\UserInviteController');
            $api->resource('coupons', 'App\Http\Controllers\CouponController');

            $api->resource('addresses', 'App\Http\Controllers\AddressController');
            $api->resource('pricing-rules', 'App\Http\Controllers\PricingRuleController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('messages', 'App\Http\Controllers\MessageController');
            $api->resource('message-threads', 'App\Http\Controllers\MessageThreadController');
            $api->resource('administrators', 'App\Http\Controllers\AdminController');
            $api->resource('ingredients', 'App\Http\Controllers\IngredientController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('products', 'App\Http\Controllers\ProductController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('materials', 'App\Http\Controllers\MaterialController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('customers', 'App\Http\Controllers\CustomerController');
            $api->resource('items', 'App\Http\Controllers\ItemController');
            $api->resource('qc-test-methods', 'App\Http\Controllers\QcTestMethodController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('qc-tests', 'App\Http\Controllers\QcTestController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('packaging-options', 'App\Http\Controllers\PackagingOptionController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('pricing-addons', 'App\Http\Controllers\PricingAddonController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('pricing-addon-tiers', 'App\Http\Controllers\PricingAddonTierController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('item-lists', 'App\Http\Controllers\ItemListController', [
                'except' => ['store']
            ]);
            $api->resource('ingredient-types', 'App\Http\Controllers\IngredientTypeController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('orders', 'App\Http\Controllers\OrderController', [
                'except' => ['store']
            ]);
            $api->resource('carts', 'App\Http\Controllers\CartController', [
                'except' => ['store']
            ]);
            $api->resource('quotes', 'App\Http\Controllers\QuoteController', [
                'except' => ['store']
            ]);
            $api->resource('formulas', 'App\Http\Controllers\FormulaController', [
                'except' => ['index', 'show']
            ]);
            $api->post('formulas/clone', 'App\Http\Controllers\FormulaController@duplicateFormula');

            $api->resource('formula-ingredients', 'App\Http\Controllers\FormulaIngredientController', [
                'except' => ['store']
            ]);
            $api->resource('item-packaging-options', 'App\Http\Controllers\ItemPackagingOptionController', [
                'except' => ['store']
            ]);
            $api->resource('item-qc-test-methods', 'App\Http\Controllers\ItemQcTestMethodController', [
                'except' => ['store']
            ]);
            $api->resource('item-pricing-addon-tiers', 'App\Http\Controllers\ItemPricingAddonTierController', [
                'except' => ['store']
            ]);

            $api->resource('custom-ingredients', 'App\Http\Controllers\CustomIngredientController', [
                'except' => ['store']
            ]);

            $api->resource('documentations', 'App\Http\Controllers\DocumentationController');
            $api->post('users/change-password', 'App\Http\Controllers\UserController@change_password');
            $api->resource('catalogs', 'App\Http\Controllers\CatalogController', [
                'except' => ['index', 'show']
            ]);

            $api->resource('favorites', 'App\Http\Controllers\FavoriteController');
            $api->resource('pods', 'App\Http\Controllers\PodController');

            // ItemListable URLs
            $api->post('orders/place', ['App\Http\Controllers\PlaceItemListable', 'placeOrder']);
            $api->post('quotes/place', ['App\Http\Controllers\PlaceItemListable', 'placeQuote']);
            $api->post('carts/place', ['App\Http\Controllers\PlaceItemListable', 'placeCart']);

            // Order transfer api
            $api->post('orders/transfer', 'App\Http\Controllers\ItemListableTransferController');

            // NBAI APIs Below this line
            $api->resource('nb-recommendations', 'App\Http\Controllers\NBAI\NbRecommendationsController');
            $api->resource('gene-protein-expressions', 'App\Http\Controllers\NBAI\GeneProteinExpressionController');
            $api->resource('cell-types', 'App\Http\Controllers\CellTypeController');
            $api->resource('critical-quality-attributes', 'App\Http\Controllers\NBAI\CriticalQualityAttributeController');
            $api->resource('cell-media', 'App\Http\Controllers\NBAI\CellMediaController');
            $api->resource('journals', 'App\Http\Controllers\NBAI\JournalController');
            $api->resource('expression-types', 'App\Http\Controllers\NBAI\ExpressionTypeController');
            $api->get('most-cited-media', 'App\Http\Controllers\NBAI\NBAIChartController@getMostCitedPaper');
            $api->get('calculate-concentration', 'App\Http\Controllers\NBAI\NBAIChartController@calculateConcentration');
            $api->get('gene-protein-recommendations', 'App\Http\Controllers\NBAI\NBAIChartController@geneProteinRecommendations');
            $api->resource('user-interests', 'App\Http\Controllers\NBAI\UserInterestController');
            $api->resource('seats', 'App\Http\Controllers\NBAI\NBAISeatController');
            $api->resource('recommendations', 'App\Http\Controllers\NBAI\RecommendationsController', [
                'except' => 'index'
            ]);
            $api->resource('product-options', 'App\Http\Controllers\ProductOptionController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('product-documentations', 'App\Http\Controllers\ProductDocumentController', [
                'except' => ['index', 'show']
            ]);
            $api->resource('recommended-products', 'App\Http\Controllers\RecommendedProductController', [
                'except' => ['index', 'show']
            ]);

            $api->resource('change-logs', 'App\Http\Controllers\ItemListChangeLogController');

            // Krakatoa Routes
            $api->resource('devices', 'App\Http\Controllers\DeviceController');
            $api->resource('file-upload', 'App\Http\Controllers\FileUploadController', [
                'except' => 'store'
            ]);
            $api->get('file-upload/{file_upload}/download', 'App\Http\Controllers\FileUploadController@download_file');

            $api->resource('device-firmware', 'App\Http\Controllers\DeviceFirmwareController');
            $api->resource('firmware', 'App\Http\Controllers\FirmwareController');

            // pdf, docx, csv download temp url generating API's
            $api->get('/doc-download/{id}/document', 'App\Http\Controllers\GenerateTempUrlController');
            $api->get('/ingredient-list-export', 'App\Http\Controllers\IngredientController@tempUrlIngredientList');
        });
        $api->resource('file-upload', 'App\Http\Controllers\FileUploadController', [
            'only' => 'store'
        ]);
        $api->post('verify-user', 'App\Http\Controllers\AuthController@verifyUser');
    });

    // pod run activity log upload api
    $api->post('pod-run-activity', 'App\Http\Controllers\PodController@uploadPodRunActivityLog');

    $api->post('/password-forgot', 'App\Http\Controllers\AuthController@forgotPassword');
    $api->put('/password-forgot', 'App\Http\Controllers\AuthController@saveNewPassword');
});
