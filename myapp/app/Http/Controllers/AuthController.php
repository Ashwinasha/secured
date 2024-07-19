<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\EmailVerificationCode;
use App\Models\PasswordResetCode;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // Registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send email verification code
        $this->sendVerificationCode($user);

        return redirect()->route('login')->with('message', 'User registered successfully. Please verify your email.');
    }

    // Login
    // app/Http/Controllers/AuthController.php
public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return redirect()->back()->withErrors(['message' => 'Invalid login credentials']);
    }

    if (!$user->email_verified_at) {
        return redirect()->back()->withErrors(['message' => 'Please verify your email before logging in']);
    }

    Auth::login($user);
    return redirect('/home')->with('message', 'Logged in successfully');
}


    // Send Verification Code
    private function sendVerificationCode($user)
{
    $code = Str::random(32);
    EmailVerificationCode::updateOrCreate([
        'user_id' => $user->id,
    ], [
        'code' => $code,
        'expires_at' => Carbon::now()->addMinutes(60),
    ]);

    $verificationLink = route('verify.email', ['code' => $code]);

    Mail::send('emails.verify', ['verificationLink' => $verificationLink, 'user' => $user], function ($message) use ($user) {
        $message->to($user->email);
        $message->subject('Email Verification');
    });
}


    // Verify Email
    public function verifyEmail($code)
    {
        $verificationCode = EmailVerificationCode::where('code', $code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verificationCode) {
            return redirect('/login')->withErrors(['message' => 'Invalid or expired verification link.']);
        }

        $user = $verificationCode->user;
        $user->email_verified_at = Carbon::now();
        $user->save();

        $verificationCode->delete();

        return redirect('/login')->with('message', 'Email verified successfully. You can now login.');
    }

    // Reset Password
   
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }



    public function forgotPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return redirect()->back()->withErrors(['message' => 'User not found']);
    }

    $code = Str::random(6);
    PasswordResetCode::create([
        'user_id' => $user->id,
        'code' => $code,
        'expires_at' => now()->addMinutes(60),
    ]);

    $resetLink = route('password.reset', ['code' => $code]);

    Mail::send('emails.password_reset', ['resetLink' => $resetLink, 'user' => $user], function ($message) use ($user) {
        $message->to($user->email);
        $message->subject('Password Reset Request');
    });

    return redirect()->route('login')->with('message', 'Password reset link sent to your email');
}
    // Reset Password
    public function showResetPasswordForm($code)
{
    return view('auth.reset_password', ['code' => $code]);
}

    
public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email',
        'code' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $reset = PasswordResetCode::where('code', $request->code)
        ->where('expires_at', '>', now())
        ->first();

    if (!$reset) {
        return redirect()->back()->withErrors(['message' => 'Invalid or expired reset code']);
    }

    $user = User::where('email', $request->email)->first();
    if ($user->id !== $reset->user_id) {
        return redirect()->back()->withErrors(['message' => 'Reset code does not match user']);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    // Optionally, delete the reset code after successful password change
    $reset->delete();

    return redirect()->route('login')->with('message', 'Password reset successfully');
}

// AuthController.php

public function resendVerification(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return redirect()->back()->withErrors(['email' => 'No user found with this email address.']);
    }

    if ($user->email_verified_at) {
        return redirect()->back()->with('message', 'Email is already verified.');
    }

    // Send verification code again
    $this->sendVerificationCode($user);

    return redirect()->back()->with('message', 'Verification email resent. Please check your inbox.');
}



}