<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Finance\Models\Due;
use App\Domain\Finance\Models\Invoice;
use App\Domain\Finance\Models\Payment;
use App\Domain\Membership\Models\Member;
use App\Support\Enums\DueStatus;
use App\Support\Enums\MemberStatus;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\PaymentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_polymorphic_payment_for_due_and_invoice_generation(): void
    {
        $user = User::factory()->create();
        $member = Member::create([
            'user_id' => $user->id,
            'name' => 'Hendryk Anggota',
            'status' => MemberStatus::Active,
        ]);

        $due = Due::create([
            'member_id' => $member->id,
            'period_year' => 2025,
            'amount' => 150000,
            'status' => DueStatus::Pending,
            'due_date' => '2025-03-31',
            'notes' => 'Iuran Wajib 2025',
        ]);

        $this->assertSame(150000.00, (float) $due->amount);
        $this->assertSame('Pending', $due->status->name);

        // Create polymorphic payment pointing to due
        $payment = Payment::create([
            'payable_type' => Due::class,
            'payable_id' => $due->id,
            'member_id' => $member->id,
            'amount' => 150000,
            'method' => PaymentMethod::BankTransfer,
            'bank_name' => 'BCA',
            'account_name' => 'Hendryk Anggota',
            'account_number' => '0987654321',
            'status' => PaymentStatus::Pending,
        ]);

        // Assert relationships
        $this->assertInstanceOf(Due::class, $payment->payable);
        $this->assertSame($due->id, $payment->payable->id);

        // Verify payment
        $payment->update([
            'status' => PaymentStatus::Verified,
            'paid_at' => now(),
            'verified_at' => now(),
            'verified_by' => $user->id,
        ]);
        $due->update(['status' => DueStatus::Paid]);

        $invoice = Invoice::create([
            'payment_id' => $payment->id,
            'invoice_number' => 'INV-2025-001',
            'amount' => 150000,
            'tax' => 0,
            'total' => 150000,
            'issued_at' => now(),
        ]);

        $this->assertSame(PaymentStatus::Verified, $payment->fresh()->status);
        $this->assertSame(DueStatus::Paid, $due->fresh()->status);
        $this->assertSame('INV-2025-001', $payment->invoice->invoice_number);
    }
}
