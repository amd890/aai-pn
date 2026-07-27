<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certification_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('certification_participants')->cascadeOnDelete();
            $table->string('type', 30); // application, portfolio, evidence, etc
            $table->string('name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->boolean('verified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['participant_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certification_documents');
    }
};
