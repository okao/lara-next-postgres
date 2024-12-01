<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->boolean('is_default')->default(false);
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('permissions', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->string('module')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('role_user', function (Blueprint $table) {
      $table->foreignId('role_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->primary(['role_id', 'user_id']);
      $table->timestamps();
    });

    Schema::create('permission_role', function (Blueprint $table) {
      $table->foreignId('permission_id')->constrained()->onDelete('cascade');
      $table->foreignId('role_id')->constrained()->onDelete('cascade');
      $table->primary(['permission_id', 'role_id']);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('permission_role');
    Schema::dropIfExists('role_user');
    Schema::dropIfExists('permissions');
    Schema::dropIfExists('roles');
  }
};
