<?php

namespace App\Traits;

use App\Models\Role;

trait HasDefaultRole
{
  public static function bootHasDefaultRole()
  {
    static::created(function ($user) {
      if ($user->email_verified_at) {
        $defaultRole = Role::where('is_default', true)->first();
        if ($defaultRole) {
          $user->roles()->attach($defaultRole->id);
        }
      }
    });
  }

  public function assignDefaultRole()
  {
    $defaultRole = Role::where('is_default', true)->first();
    if ($defaultRole && !$this->hasRole($defaultRole->slug)) {
      $this->roles()->attach($defaultRole->id);
    }
  }
}
