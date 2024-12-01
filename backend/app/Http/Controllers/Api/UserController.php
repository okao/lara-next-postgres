<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function register(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validation Error',
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'status' => true,
        'verification_code' => '123456',
        'verification_code_expires_at' => now()->addMinutes(10),
      ]);

      return response()->json([
        'status' => true,
        'message' => 'User registered successfully. Use code 123456 to verify your account.',
        'user' => $user
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Registration failed',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function verifyEmail(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'code' => 'required|string|size:6',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validation Error',
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::where('email', $request->email)->first();

      if ($user->verification_code !== $request->code) {
        return response()->json([
          'status' => false,
          'message' => 'Invalid verification code'
        ], 400);
      }

      if ($user->verification_code_expires_at->isPast()) {
        return response()->json([
          'status' => false,
          'message' => 'Verification code has expired'
        ], 400);
      }

      if ($user->hasVerifiedEmail()) {
        return response()->json([
          'status' => false,
          'message' => 'Email already verified'
        ], 400);
      }

      $user->markEmailAsVerified();
      $user->verification_code = null;
      $user->verification_code_expires_at = null;
      $user->save();
      $user->assignDefaultRole();

      return response()->json([
        'status' => true,
        'message' => 'Email verified successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Verification failed',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function resendVerification(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validation Error',
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::where('email', $request->email)->first();

      if ($user->hasVerifiedEmail()) {
        return response()->json([
          'status' => false,
          'message' => 'Email already verified'
        ], 400);
      }

      $user->sendEmailVerificationNotification();

      return response()->json([
        'status' => true,
        'message' => 'Verification link sent successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to resend verification email',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
