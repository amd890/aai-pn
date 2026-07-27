<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('certificate_number', 50)->unique();
            $table->string('type')->nullable(); // keanggotaan, penghargaan, etc
            $table->string('qr_code')->nullable();
            $table->string('signed_by')->nullable();
            $table->string('signer_position')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_certificates');
    }
};
