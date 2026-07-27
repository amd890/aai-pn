<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained('event_registrations')->cascadeOnDelete();
            $table->string('certificate_number', 50)->unique();
            $table->string('qr_code')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->unique('event_registration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_certificates');
    }
};
