<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections')->cascadeOnDelete();
            $table->string('voter_hash'); // hashed member identity for anonymization
            $table->foreignId('candidate_id')->constrained('election_candidates')->cascadeOnDelete();
            $table->boolean('otp_verified')->default(false);
            $table->timestamp('voted_at');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            // Ensure one vote per voter per election
            $table->unique(['election_id', 'voter_hash']);
            $table->index(['election_id', 'candidate_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_votes');
    }
};
