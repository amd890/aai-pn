<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('payable'); // polymorphic: dues, event_registrations, etc.
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method', 30)->nullable(); // bank_transfer, gateway, cash, qris
            $table->string('gateway_name')->nullable(); // midtrans, xendit, etc
            $table->string('gateway_ref')->nullable(); // transaction id from gateway
            $table->string('payment_proof')->nullable(); // file path for manual transfer proof
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('status', 20)->default('pending'); // pending, verified, rejected, refunded, expired
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'status']);
            $table->index('gateway_ref');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
