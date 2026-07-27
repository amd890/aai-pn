<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('level', 20); // nasional, wilayah, cabang
            $table->string('type', 20)->default('pemilihan'); // pemilihan, polling
            $table->foreignId('organization_unit_id')->nullable()->constrained('organization_units')->nullOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('status', 20)->default('draft'); // draft, open, closed, counted
            $table->unsignedTinyInteger('max_vote')->default(1);
            $table->boolean('require_otp')->default(true);
            $table->string('featured_image')->nullable();
            $table->json('eligible_criteria')->nullable(); // kriteria pemilih
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['level', 'status']);
            $table->index(['start_at', 'end_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
