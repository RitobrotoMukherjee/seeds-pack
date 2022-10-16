<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('home');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

Route::middleware('auth')->group(function(){
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    Route::get('/report', [App\Http\Controllers\ReportController::class, 'getReport'])->name('get.report');
    Route::prefix('product')->group(function(){
        Route::get('/delete/{id}', [App\Http\Controllers\ProductController::class, 'deleteProduct'])->name('product.delete');
        Route::get('/list', [App\Http\Controllers\ProductController::class, 'productList'])->name('product.list');
        Route::post('/server-list', [App\Http\Controllers\ProductController::class, 'serverList'])->name('product.server.list');
        Route::get('/detail/{id}', [App\Http\Controllers\ProductController::class, 'productDetail'])->name('product.detail');
        Route::get('/add', [App\Http\Controllers\ProductController::class, 'productAdd'])->name('product.add');
        Route::post('/save', [App\Http\Controllers\ProductController::class, 'upsertProduct'])->name('product.upsert');
    });
    Route::prefix('customer')->group(function(){
        Route::get('/list', [App\Http\Controllers\CustomerController::class, 'customerList'])->name('customer.list');
        Route::post('/server-list', [App\Http\Controllers\CustomerController::class, 'serverList'])->name('customer.server.list');
    });
    Route::prefix('billing')->group(function(){
        Route::get('/list', [App\Http\Controllers\BillingController::class, 'billingList'])->name('billing.list');
        Route::post('/server-list', [App\Http\Controllers\BillingController::class, 'serverList'])->name('billing.server.list');
        Route::get('/generate', [App\Http\Controllers\BillingController::class, 'generateBill'])->name('bill.generate');
        Route::post('/save', [App\Http\Controllers\BillingController::class, 'upsertBill'])->name('bill.upsert');
        Route::get('/print/{id}', [App\Http\Controllers\BillingController::class, 'printBill'])->name('bill.print');
        Route::get('/delete/{id}', [App\Http\Controllers\BillingController::class, 'delete'])->name('bill.delete');
    });
    Route::prefix('payment')->group(function(){
        Route::get('/list', [App\Http\Controllers\PaymentController::class, 'paymentList'])->name('payment.list');
        Route::post('/server-list', [App\Http\Controllers\PaymentController::class, 'serverList'])->name('payment.server.list');
        Route::get('/add', [App\Http\Controllers\PaymentController::class, 'paymentAdd'])->name('payment.add');
        Route::post('/save', [App\Http\Controllers\PaymentController::class, 'upsertPayment'])->name('payment.upsert');
    });
    Route::prefix('report')->group(function(){
        Route::get('/view-customer', [App\Http\Controllers\ReportController::class, 'reportView'])->name('customer.view');
        Route::get('/customer', [App\Http\Controllers\ReportController::class, 'customerReport'])->name('customer.report');
    });
    
    Route::prefix('ajax')->group(function(){
        Route::post('get/product', [App\Http\Controllers\AjaxController::class, 'getProductById'])->name('ajax.product.detail');
        Route::post('get/customer', [App\Http\Controllers\AjaxController::class, 'getCustomerById'])->name('ajax.customer.detail');
        Route::post('add/product', [App\Http\Controllers\AjaxController::class, 'productCart'])->name('ajax.product.cart');
    });
});
