<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;

// Define routes for payment pages
Route::get('payment', [PaymentController::class, 'paymentPage'])->name('payment.page');
Route::post('process-payment', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('payment-confirmation', [PaymentController::class, 'paymentConfirmation'])->name('payment.confirmation');

Route::get('/', function () {
    return view('welcome');
});
