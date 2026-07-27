<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lsp_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('certification_participants')->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained('certification_schemes')->cascadeOnDelete();
            $table->string('certificate_number', 50)->unique();
            $table->string('qr_code')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status', 20)->default('active'); // active, expired, revoked
            $table->timestamps();

            $table->index(['participant_id', 'status']);
            $table->index('certificate_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lsp_certificates');
    }
};
