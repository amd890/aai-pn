<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('type', 30); // ktp, sk_pengangkatan, ijazah, sertifikat, etc
            $table->string('name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable(); // in bytes
            $table->string('mime_type')->nullable();
            $table->boolean('verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_documents');
    }
};
