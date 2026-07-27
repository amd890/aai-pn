<?php

namespace App\Livewire\Admin\Finance;

use App\Domain\Finance\Models\Due;
use App\Domain\Finance\Models\Payment;
use App\Support\Enums\DueStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PaymentVerification extends Component
{
    use WithPagination;

    public string $noticeMessage = '';

    public function verifyPayment(int $paymentId, \App\Domain\Finance\Services\FinanceService $financeService)
    {
        $payment = Payment::with('payable')->findOrFail($paymentId);

        if (($payment->status->value ?? $payment->status) === 'pending') {
            $payment->update([
                'status' => 'verified',
                'paid_at' => now(),
                'verified_at' => now(),
                'verified_by' => auth()->id() ?? 1,
            ]);

            if ($payment->payable && $payment->payable instanceof Due) {
                $payment->payable->update(['status' => DueStatus::Paid]);
            }

            $invoice = $financeService->issueInvoice($payment);

            $this->noticeMessage = "Pembayaran untuk Invoice [{$invoice->invoice_number}] berhasil diverifikasi oleh Bendahara!";
        }
    }

    public function render()
    {
        $payments = Payment::with('payable')->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.finance.payment-verification', compact('payments'));
    }
}
