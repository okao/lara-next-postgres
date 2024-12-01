<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            if (!$user->hasVerifiedEmail()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not verified'
                ], 403);
            }

            $token = $user->createToken(
                $request->userAgent(),
                $request->ip()
            );

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'access_token' => $token->access_token,
                    'refresh_token' => $token->refresh_token,
                    'token_type' => 'Bearer',
                    'expires_in' => $token->access_token_expires_at->diffInSeconds(now())
                ]
            ])->cookie(
                'access_token',
                $token->access_token,
                60,
                '/',
                null,
                false, // secure
                false  // httpOnly
            )->cookie(
                'refresh_token',
                $token->refresh_token,
                60 * 24 * 7,
                '/',
                null,
                false,
                false
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'No token provided'
                ], 400);
            }

            Token::where('access_token', $token)->update(['is_revoked' => true]);

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to get user info',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'refresh_token' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $token = Token::where('refresh_token', $request->refresh_token)
                ->where('is_revoked', false)
                ->first();

            if (!$token || $token->isRefreshTokenExpired()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid refresh token'
                ], 401);
            }

            // Revoke old token
            $token->update(['is_revoked' => true]);

            // Create new token
            $newToken = $token->user->createToken(
                $token->device,
                $token->ip_address
            );

            return response()->json([
                'status' => true,
                'message' => 'Token refreshed successfully',
                'data' => [
                    'access_token' => $newToken->access_token,
                    'refresh_token' => $newToken->refresh_token,
                    'token_type' => 'Bearer',
                    'expires_in' => $newToken->access_token_expires_at->diffInSeconds(now())
                ]
            ])->cookie('access_token', $newToken->access_token, 60)
                ->cookie('refresh_token', $newToken->refresh_token, 60 * 24 * 7);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to refresh token',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
