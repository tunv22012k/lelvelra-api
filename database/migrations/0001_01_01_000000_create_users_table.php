<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 200);
            $table->boolean('active_flg')->default(false);
            $table->string('avatar', 200)->nullable();
            $table->char('sex', 1); // 1 -> nam, 2 -> nữ, 3 -> other
            $table->char('role', 2); // 01 -> user, 02 -> người bán hàng, 03: admin
            $table->string('phone', 13)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('lat', 20)->nullable();
            $table->string('lon', 20)->nullable();
            $table->string('description', 1000)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
