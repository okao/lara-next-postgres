<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Token extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'user_id',
    'access_token',
    'refresh_token',
    'access_token_expires_at',
    'refresh_token_expires_at',
    'device',
    'ip_address',
    'is_revoked'
  ];

  protected $casts = [
    'access_token_expires_at' => 'datetime',
    'refresh_token_expires_at' => 'datetime',
    'is_revoked' => 'boolean'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function isAccessTokenExpired(): bool
  {
    return $this->access_token_expires_at->isPast();
  }

  public function isRefreshTokenExpired(): bool
  {
    return $this->refresh_token_expires_at->isPast();
  }
}
