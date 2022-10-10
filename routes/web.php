<?php

use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\QuotationController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::view('/welcome1', 'welcome1');
Route::view('/getQuotation', 'quotation');
Route::resource('prescription', PrescriptionController::class);
Route::resource('quotation', QuotationController::class);

Route::get('view_prescriptions', [QuotationController::class, 'view_prescriptions'])->name('view_prescriptions');
Route::get('create_quotation_view', [QuotationController::class, 'create_quotation_view']);
Route::get('pdf', [QuotationController::class, 'quotation_pdf_generator'])->name('pdf');