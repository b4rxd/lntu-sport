<?php

use App\Http\Controllers\Card\CardController;
use App\Http\Controllers\Location\LocationController;
use App\Http\Controllers\Price\PriceController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Report\SaleReportController;
use App\Http\Controllers\Reversal\ReversalController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::post('/postLogin', [AuthController::class, 'postLogin'])->name('post-login');

Route::middleware('auth')->group(function () {
    Route::post('/postLogout', [AuthController::class, 'postLogout'])->name('logout');

    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/{id}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('/locations/{id}', [LocationController::class, 'update'])->name('locations.update');
    Route::patch('/locations/{id}/toggle', [LocationController::class, 'toggle'])->name('locations.toggle');
    Route::delete('/locations/{id}', [LocationController::class, 'destroy'])->name('locations.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::post('/products/prices', [PriceController::class, 'store'])->name('prices.store');
    Route::patch('/prices/{id}/toggle', [PriceController::class, 'toggle'])->name('prices.toggle');

    Route::get('/card/create/{id}', [CardController::class, 'create'])->name('card.create');
    Route::post('/card/create', [CardController::class, 'store'])->name('card.store');
    Route::get('/card/create-external/{id}', [CardController::class, 'createExternal'])->name('card.createExternal');
    Route::post('/card/create-external', [CardController::class, 'storeExternal'])->name('card.storeExternal');
    Route::get('/card/print/view/{id}', [CardController::class, 'print'])->name('name.print');
    Route::get('/card/print/pdf/{id}', [CardController::class, 'printPdf'])->name('card.print.pdf');

    Route::get('/reversal', [ReversalController::class, 'index'])->name('reversal.index');
    Route::get('/reversal/create', [ReversalController::class, 'create'])->name('reversal.create');
    Route::post('reversal/store', [ReversalController::class, 'store'])->name('reversal.store');

    Route::get('/report/sale', [SaleReportController::class, 'index'])->name('report.sale.index');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create-user', [UserController::class, 'createUser'])->name('user.createUser');
    Route::get('/user/create-admin', [UserController::class, 'createAdmin'])->name('user.createAdmin');
    Route::post('/user/create-user', [UserController::class, 'storeUser'])->name('user.storeUser');
    Route::post('/user/store-admin', [UserController::class, 'storeAdmin'])->name('user.storeAdmin');
    Route::patch('/user/toggle/{id}', [UserController::class, 'toggle'])->name('user.toggle');
});

Route::get('/card/info', [CardController::class, 'info'])->name('card.info');
Route::post('/card/info/{barcode}', [CardController::class, 'indexInfo'])->name('card.indexInfo');