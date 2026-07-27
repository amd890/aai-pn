<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('card_number', 30)->unique();
            $table->string('qr_code')->nullable(); // path to QR image
            $table->text('qr_data')->nullable(); // encoded data in QR
            $table->string('template')->nullable(); // card design template
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('status', 20)->default('active'); // active, expired, revoked
            $table->timestamps();

            $table->index(['member_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_cards');
    }
};
