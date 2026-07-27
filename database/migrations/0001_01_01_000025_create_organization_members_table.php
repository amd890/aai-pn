<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // organization_members depends on members table, so it's placed after members
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_period_id')->constrained('organization_periods')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('position'); // jabatan dalam kepengurusan
            $table->string('position_category')->nullable(); // ketua, sekretaris, bendahara, anggota, dll
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->unique(['organization_period_id', 'member_id']);
            $table->index(['organization_period_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};
