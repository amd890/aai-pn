<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('license_number', 50)->unique();
            $table->date('license_expired_at')->nullable();
            $table->text('specialization')->nullable();
            $table->string('status', 20)->default('active'); // active, inactive, expired
            $table->timestamps();

            $table->index(['status', 'license_expired_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessors');
    }
};
