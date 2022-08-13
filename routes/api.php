<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\AuthController;

use App\Http\Controllers\api\v1\StockproductController;
use App\Http\Controllers\api\v1\StockproductgroupController;
use App\Http\Controllers\api\v1\SaleproductController;
use App\Http\Controllers\api\v1\SaleproductgroupController;
use App\Http\Controllers\api\v1\PurchaseproductController;
use App\Http\Controllers\api\v1\ComboController;
use App\Http\Controllers\api\v1\SaleController;
use App\Http\Controllers\api\v1\IvaconditionController;
use App\Http\Controllers\api\v1\DoctypeController;
use App\Http\Controllers\api\v1\ClientController;
use App\Http\Controllers\api\v1\SupplierController;
use App\Http\Controllers\api\v1\EmpresaController;
use App\Http\Controllers\api\v1\SucursalController;

use App\Http\Controllers\api\v1\IvaaliquotController;
use App\Http\Controllers\api\v1\ModelofactController;

use App\Http\Controllers\api\v1\ComprobanteController;

use App\Http\Controllers\api\v1\DevolutionController;
use App\Http\Controllers\api\v1\CreditnoteController;
use App\Http\Controllers\api\v1\DebitnoteController;
use App\Http\Controllers\api\v1\PaymentmethodController;

use App\Http\Controllers\api\v1\UserController;

use App\Http\Controllers\api\v1\CajaController;
use App\Http\Controllers\api\v1\PaymentController;
use App\Http\Controllers\api\v1\RefundController;

use App\Http\Controllers\api\v1\PurchaseorderController;

use App\Http\Controllers\api\v1\StockmovementController;

use App\Http\Controllers\api\v1\TagController;



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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 */
Route::prefix('v1')->group(static function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::get('stockproducts/stock', [StockproductController::class,'get_stock']);
    Route::resource('stockproducts', StockproductController::class)->only(['index', 'show', 'update', 'store']);
    Route::resource('stockproductgroups', StockproductgroupController::class)->only(['index', 'show', 'update', 'store']);

    
    Route::put('stockproducts/{id}/update_values', [StockproductController::class, 'update_values']);
    Route::post('stockproducts/{id}/updload_image', [StockproductController::class, 'updload_image']);
    Route::put('stockproducts/{id}/remove_image', [StockproductController::class, 'remove_image']);
    Route::get('get_stockproducts_select', [StockproductController::class, 'get_stockproducts_select']);
    Route::get('saleproducts/search_barcode', [SaleproductController::class, 'search_barcode']);
    Route::resource('saleproducts', SaleproductController::class)->only(['index', 'show', 'update', 'store']);
    Route::resource('saleproductgroups', SaleproductgroupController::class)->only(['index', 'show', 'update', 'store']);
    Route::put('saleproducts/{id}/update_values', [SaleproductController::class, 'update_values']);
    Route::post('saleproducts/{id}/updload_image', [SaleproductController::class, 'updload_image']);
    Route::put('saleproducts/{id}/remove_image', [SaleproductController::class, 'remove_image']);
    Route::resource('purchaseproducts', PurchaseproductController::class)->only(['index', 'show', 'update', 'store']);
    
    Route::resource('combos', ComboController::class)->only(['index', 'show']);
    Route::put('combos/{id}/update_values', [ComboController::class, 'update_values']);

    
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::resource('purchaseorders', PurchaseorderController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::resource('stockmovements', StockmovementController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('stockmovements/new', [StockmovementController::class, 'new']);

    Route::resource('empresas', EmpresaController::class)->only(['index', 'show']);
    Route::resource('sucursals', SucursalController::class)->only(['index', 'show', 'update', 'store']);

    Route::resource('sales', SaleController::class)->only(['index', 'show', 'store']);
    Route::get('/sales/{id}/make_devolution', [SaleController::class, 'make_devolution']);
    Route::get('get_sale_products_venta', [SaleproductController::class, 'get_sale_products_venta']);
    Route::resource('ivaconditions', IvaconditionController::class)->only(['index']);
    Route::resource('doctypes', DoctypeController::class)->only(['index']);
    Route::resource('clients', ClientController::class)->only(['index', 'update', 'show', 'store']);
    Route::post('suppliers/make_order', [SupplierController::class, 'make_order']);
    Route::resource('suppliers', SupplierController::class)->only(['index', 'update', 'show', 'store']);
    Route::get('get_suppliers_select', [SupplierController::class, 'get_suppliers_select']);
    

    Route::resource('ivaaliquots', IvaaliquotController::class)->only(['index']);
    Route::resource('modelofacts', ModelofactController::class)->only(['index']);

    Route::post('comprobantes/facts', [ComprobanteController::class, 'make_fact']);
    Route::post('comprobantes/nc', [ComprobanteController::class, 'make_nc']);
    Route::post('comprobantes/nd', [ComprobanteController::class, 'make_nd']);
    Route::post('comprobantes/nc_from_devolution', [ComprobanteController::class, 'make_nc_from_devolution']);

    Route::resource('devolutions', DevolutionController::class)->only(['store']);
    Route::resource('creditnotes', CreditnoteController::class)->only(['store']);
    Route::resource('debitnotes', DebitnoteController::class)->only(['store']);


    Route::resource('paymentmethods', PaymentmethodController::class)->only(['index']);

    Route::resource('users', UserController::class)->only(['index']);

    Route::get('cajas/find/{id}', [CajaController::class, 'find']);
    Route::resource('payments', PaymentController::class)->only(['store']);
    Route::resource('refunds', RefundController::class)->only(['store']);

    Route::get('get_tags_select', [TagController::class, 'get_tags_select']);
    Route::resource('tags', TagController::class)->only(['index', 'update', 'show', 'store']);

});