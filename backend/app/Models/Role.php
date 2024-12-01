<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'slug',
    'description',
    'is_default'
  ];

  protected $casts = [
    'is_default' => 'boolean'
  ];

  public function users(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'role_user')
      ->withTimestamps();
  }

  public function permissions(): BelongsToMany
  {
    return $this->belongsToMany(Permission::class, 'permission_role')
      ->withTimestamps();
  }
}
