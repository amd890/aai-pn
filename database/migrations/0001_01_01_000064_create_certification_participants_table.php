<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certification_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('certification_batches')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('status', 20)->default('registered'); // registered, assessed, competent, not_competent
            $table->date('assessment_date')->nullable();
            $table->text('result')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['batch_id', 'member_id']);
            $table->index(['batch_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certification_participants');
    }
};
