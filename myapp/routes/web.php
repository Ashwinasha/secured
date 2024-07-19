<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Default welcome route
Route::get('/', function () {
    return view('welcome');
});

// Registration Routes
Route::get('register', function () {
    return view('register');
});
Route::post('register', [AuthController::class, 'register'])->name('register');

// Login Routes
Route::get('login', function () {
    return view('login');
});
Route::post('login', [AuthController::class, 'login'])->name('login');

// Email Verification Route
Route::get('email/verify/{code}', [AuthController::class, 'verifyEmail'])->name('verify.email');

// Resend Verification Route
Route::get('email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend');

// Forgot Password Routes
Route::get('password/reset', function () {
    return view('password_reset');
});

Route::get('home', function () {
    return view('home');
});



Route::post('resend-verification', [AuthController::class, 'resendVerificationCode'])->name('verification.resend');
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password.form');
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');


// Route to show the password reset form
Route::get('reset-password/{code}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');

// Route to handle the password reset form submission
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// web.php
Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('verification.resend');



