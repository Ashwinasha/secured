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
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send email verification code
        $this->sendVerificationCode($user);

        return response()->json(['message' => 'User registered successfully. Please verify your email.'], 201);
    }

    // Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        return response()->json(['message' => 'Login successful'], 200);
    }

    // Send Verification Code
    private function sendVerificationCode($user)
{
    $code = Str::random(32); // Use a longer random string for better security
    EmailVerificationCode::create([
        'user_id' => $user->id,
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


    public function forgotPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $code = Str::random(6);
    PasswordResetCode::create([
        'user_id' => $user->id,
        'code' => $code,
        'expires_at' => Carbon::now()->addMinutes(60),
    ]);

    Mail::send('emails.password_reset', ['code' => $code, 'user' => $user], function ($message) use ($user) {
        $message->to($user->email);
        $message->subject('Password Reset Code');
    });

    return response()->json(['message' => 'Password reset code sent to your email'], 200);
}


    // Reset Password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'code' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $reset = PasswordResetCode::where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$reset) {
            return response()->json(['message' => 'Invalid or expired reset code'], 400);
        }

        $user = User::where('email', $request->email)->first();
        if ($user->id !== $reset->user_id) {
            return response()->json(['message' => 'Reset code does not match user'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }
}
