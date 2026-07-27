<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certification_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('certification_schemes')->cascadeOnDelete();
            $table->foreignId('tuk_id')->constrained('tuks')->cascadeOnDelete();
            $table->string('batch_number', 30)->unique();
            $table->date('scheduled_date');
            $table->date('end_date')->nullable();
            $table->unsignedInteger('quota')->nullable();
            $table->foreignId('assessor_id')->nullable()->constrained('assessors')->nullOnDelete();
            $table->string('status', 20)->default('planned'); // planned, open, closed, completed
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['scheme_id', 'status']);
            $table->index('scheduled_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certification_batches');
    }
};
