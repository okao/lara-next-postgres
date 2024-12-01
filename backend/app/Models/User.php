<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasDefaultRole;

class User extends Authenticatable implements MustVerifyEmail
{
  use HasFactory, Notifiable, SoftDeletes, HasDefaultRole;

  protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'status',
    'email_verified_at',
    'verification_code',
    'verification_code_expires_at'
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'verification_code_expires_at' => 'datetime',
    'password' => 'hashed',
    'status' => 'boolean',
  ];

  protected static function boot()
  {
    parent::boot();

    static::created(function ($user) {
      // Create empty profile
      $user->profile()->create();

      // Auto verify for now
      $user->markEmailAsVerified();
      $user->assignDefaultRole();
    });
  }

  public function tokens(): HasMany
  {
    return $this->hasMany(Token::class);
  }

  public function createToken(string $device = null, string $ipAddress = null): Token
  {
    return $this->tokens()->create([
      'access_token' => bin2hex(random_bytes(32)),
      'refresh_token' => bin2hex(random_bytes(32)),
      'access_token_expires_at' => now()->addMinutes(config('auth.token_expiry.access', 15)),
      'refresh_token_expires_at' => now()->addDays(config('auth.token_expiry.refresh', 7)),
      'device' => $device,
      'ip_address' => $ipAddress
    ]);
  }

  public function roles(): BelongsToMany
  {
    return $this->belongsToMany(Role::class, 'role_user')
      ->withTimestamps();
  }

  public function hasRole(string $role): bool
  {
    return $this->roles()->where('slug', $role)->exists();
  }

  public function hasPermission(string $permission): bool
  {
    return $this->roles()
      ->whereHas('permissions', function ($query) use ($permission) {
        $query->where('slug', $permission);
      })->exists();
  }

  public function profile()
  {
    return $this->hasOne(Profile::class);
  }
}
