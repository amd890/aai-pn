<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('status', 20)->default('success'); // success, failed, blocked
            $table->timestamp('login_at');
            $table->timestamps();

            $table->index(['user_id', 'login_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
