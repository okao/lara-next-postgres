<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoleController extends Controller
{
  public function index()
  {
    try {
      $roles = Role::with('permissions')->get();

      return response()->json([
        'status' => true,
        'data' => [
          'roles' => $roles
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to fetch roles',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:roles,name',
        'description' => 'nullable|string',
        'permissions' => 'required|array',
        'permissions.*' => 'exists:permissions,id'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validation Error',
          'errors' => $validator->errors()
        ], 422);
      }

      $role = Role::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'description' => $request->description,
      ]);

      $role->permissions()->attach($request->permissions);

      return response()->json([
        'status' => true,
        'message' => 'Role created successfully',
        'data' => [
          'role' => $role->load('permissions')
        ]
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to create role',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function update(Request $request, Role $role)
  {
    try {
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        'description' => 'nullable|string',
        'permissions' => 'required|array',
        'permissions.*' => 'exists:permissions,id'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validation Error',
          'errors' => $validator->errors()
        ], 422);
      }

      $role->update([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'description' => $request->description,
      ]);

      $role->permissions()->sync($request->permissions);

      return response()->json([
        'status' => true,
        'message' => 'Role updated successfully',
        'data' => [
          'role' => $role->load('permissions')
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to update role',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function destroy(Role $role)
  {
    try {
      if ($role->is_default) {
        return response()->json([
          'status' => false,
          'message' => 'Cannot delete default role'
        ], 400);
      }

      $role->delete();

      return response()->json([
        'status' => true,
        'message' => 'Role deleted successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to delete role',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function assignUserRoles(Request $request, User $user)
  {
    try {
      $validator = Validator::make($request->all(), [
        'roles' => 'required|array',
        'roles.*' => 'exists:roles,id'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validation Error',
          'errors' => $validator->errors()
        ], 422);
      }

      $user->roles()->sync($request->roles);

      return response()->json([
        'status' => true,
        'message' => 'Roles assigned successfully',
        'data' => [
          'user' => $user->load('roles.permissions')
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to assign roles',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
