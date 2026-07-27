<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('module', 50); // membership, finance, voting, etc
            $table->string('action', 50); // create, update, delete, approve, etc
            $table->nullableMorphs('auditable'); // polymorphic
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['module', 'action']);
            $table->index('actor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
