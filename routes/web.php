<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\VehicleController;
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


Auth::routes();
Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/customers', [CustomerController::class, 'home'])->middleware('auth')->name('customers.home');

Route::get('/vehicles', [VehicleController::class, 'home'])->middleware('auth')->name('vehicles.home');

Route::get('/offers', [OfferController::class, 'home'])->middleware('auth')->name('offers.home');

Route::get('/offers/generate-pdf/{offerId}', [PDFController::class, 'generateOfferPDF']);
