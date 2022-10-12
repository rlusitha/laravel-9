<?php

use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\QuotationController;
use App\Mail\QuotationEmail;
use Illuminate\Support\Facades\Mail;
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
Route::resource('prescription', PrescriptionController::class)->middleware('auth');
Route::resource('quotation', QuotationController::class)->middleware(['auth', 'admin']);

Route::get('view_prescriptions', [QuotationController::class, 'view_prescriptions'])->name('view_prescriptions')->middleware(['auth', 'admin']);
Route::get('create_quotation_view', [QuotationController::class, 'create_quotation_view'])->middleware('auth');
Route::get('pdf/{id}', [QuotationController::class, 'quotation_pdf_generator'])->name('pdf')->middleware('auth');

Route::get('send_email', function() {
    $mailData = [
        'name' => 'Lusitha',
        'dob' => '20/12/1988'
    ];

    Mail::to("rlusitha@gmail.com")->send(new QuotationEmail($mailData));
    dd('Mail Sent!');
})->middleware('auth');