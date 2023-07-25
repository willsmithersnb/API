<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/health', 'HealthCheckController')->name('health');

Route::group(['domain' => config('app.lux_url')], function () {
    Route::get('/verify/{id}', '@verifyUrl')->name('verification.verify');
    Route::get('/signup')->name('user-invite');
});
Route::get('/doc-download/{id}/SpecificationSheet/', 'SpecificationSheetGeneratorController')->name('specification-sheet')->middleware('signed');
Route::get('/doc-download/{id}/OrderCostBreakdown/','OrderCostBreakdownGeneratorController')->name('orders.cost-breakdown')->middleware('signed');
Route::get('/doc-download/{id}/QuoteCostBreakdown/','QuoteCostBreakdownGeneratorController')->name('quote.cost-breakdown')->middleware('signed');
Route::get('/ingredient-list/exportcsv/', 'IngredientController@exportCSV')->name('ingredient-list-export')->middleware('signed');
Route::get('/qr-code', 'PodController@podQrDownloadLink')->name('qr-code')->middleware('signed');
