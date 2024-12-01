<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('tokens', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('access_token', 64)->unique();
      $table->string('refresh_token', 64)->unique();
      $table->timestamp('access_token_expires_at');
      $table->timestamp('refresh_token_expires_at');
      $table->string('device')->nullable();
      $table->string('ip_address', 45)->nullable();
      $table->boolean('is_revoked')->default(false);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('tokens');
  }
};
