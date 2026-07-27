<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->unsignedSmallInteger('candidate_number')->nullable();
            $table->text('vision_mission')->nullable();
            $table->text('profile_summary')->nullable();
            $table->string('photo')->nullable();
            $table->string('status', 20)->default('active'); // active, withdrawn, disqualified
            $table->unsignedInteger('vote_count')->default(0);
            $table->timestamps();

            $table->unique(['election_id', 'member_id']);
            $table->unique(['election_id', 'candidate_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_candidates');
    }
};
