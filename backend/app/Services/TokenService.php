<?php

namespace App\Services;

use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;

class TokenService
{
  public function createToken(User $user, Request $request): Token
  {
    return $user->createToken(
      $request->userAgent(),
      $request->ip()
    );
  }

  public function refreshToken(string $refreshToken): ?Token
  {
    $token = Token::where('refresh_token', $refreshToken)
      ->where('is_revoked', false)
      ->first();

    if (!$token || $token->isRefreshTokenExpired()) {
      return null;
    }

    // Revoke old token
    $token->update(['is_revoked' => true]);

    // Create new token
    return $token->user->createToken(
      $token->device,
      $token->ip_address
    );
  }

  public function revokeToken(string $accessToken): bool
  {
    return Token::where('access_token', $accessToken)
      ->update(['is_revoked' => true]);
  }

  public function validateToken(string $accessToken): ?User
  {
    $token = Token::where('access_token', $accessToken)
      ->where('is_revoked', false)
      ->first();

    if (!$token || $token->isAccessTokenExpired()) {
      return null;
    }

    return $token->user;
  }
}
