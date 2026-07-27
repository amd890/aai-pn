<?php

namespace App\Domain\Finance\Services;

use App\Domain\Finance\Models\Due;
use App\Domain\Finance\Models\Invoice;
use App\Domain\Finance\Models\Payment;
use App\Domain\Finance\Repositories\FinanceRepository;
use App\Domain\Membership\Models\Member;
use App\Support\Enums\DueStatus;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\PaymentStatus;
use App\Support\Services\BaseService;
use Illuminate\Support\Facades\DB;

class FinanceService extends BaseService
{
    public function __construct(protected FinanceRepository $financeRepository)
    {
    }

    /**
     * Generate mandatory annual dues (Iuran Wajib) upon member approval.
     */
    public function generateInitialDues(Member $member, float $amount = 150000.00): Due
    {
        return $this->transactional(function () use ($member, $amount) {
            $currentYear = (int) date('Y');

            return Due::create([
                'member_id' => $member->id,
                'period_year' => $currentYear,
                'amount' => $amount,
                'status' => DueStatus::Pending,
                'due_date' => date('Y-12-31'),
                'notes' => "Iuran Wajib Anggota Tahun {$currentYear}",
            ]);
        }, 'Failed to generate initial dues');
    }

    /**
     * Record payment transaction and alter due status upon verification.
     */
    public function recordPayment(Due $due, array $data): Payment
    {
        return $this->transactional(function () use ($due, $data) {
            $status = $data['status'] ?? PaymentStatus::Pending;

            $payment = Payment::create([
                'payable_type' => Due::class,
                'payable_id' => $due->id,
                'member_id' => $due->member_id,
                'amount' => $data['amount'] ?? $due->amount,
                'method' => $data['method'] ?? PaymentMethod::BankTransfer,
                'gateway_name' => $data['gateway_name'] ?? null,
                'gateway_ref' => $data['gateway_ref'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'account_name' => $data['account_name'] ?? null,
                'account_number' => $data['account_number'] ?? null,
                'payment_proof' => $data['payment_proof'] ?? null,
                'status' => $status,
                'paid_at' => ($status === PaymentStatus::Verified) ? now() : null,
                'verified_by' => $data['verified_by'] ?? null,
                'verified_at' => ($status === PaymentStatus::Verified) ? now() : null,
                'notes' => $data['notes'] ?? null,
            ]);

            if ($status === PaymentStatus::Verified || ($data['status_string'] ?? '') === 'verified') {
                $due->update(['status' => DueStatus::Paid]);
                $this->issueInvoice($payment);
            }

            return $payment->fresh(['invoice', 'payable']);
        }, 'Failed to record payment');
    }

    /**
     * Issue fiscal invoice with structured number (INV/AAI/{Year}/{Seq}).
     */
    public function issueInvoice(Payment $payment): Invoice
    {
        return $this->transactional(function () use ($payment) {
            if ($payment->invoice) {
                return $payment->invoice;
            }

            $year = (int) date('Y');
            $latestInvoiceNumber = $this->financeRepository->getLatestInvoiceNumberForYear($year);
            $sequence = 1;

            if ($latestInvoiceNumber) {
                $parts = explode('/', $latestInvoiceNumber);
                $sequence = ((int) end($parts)) + 1;
            }

            $formattedSeq = str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
            $invoiceNumber = "INV/AAI/{$year}/{$formattedSeq}";

            return Invoice::create([
                'payment_id' => $payment->id,
                'invoice_number' => $invoiceNumber,
                'amount' => $payment->amount,
                'tax' => 0.00,
                'total' => $payment->amount,
                'issued_at' => now(),
            ]);
        }, 'Failed to issue invoice');
    }
}
