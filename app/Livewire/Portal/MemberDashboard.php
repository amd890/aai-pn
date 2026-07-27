<?php

namespace App\Livewire\Portal;

use App\Domain\Finance\Models\Due;
use App\Domain\Finance\Models\Payment;
use App\Domain\Finance\Services\FinanceService;
use App\Domain\Membership\Models\Member;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.portal')]
class MemberDashboard extends Component
{
    public $member;
    public $dues;
    public $invoices;

    public string $selectedMethod = 'virtual_account';
    public string $paymentNotice = '';
    public int|null $payingDueId = null;

    public function mount()
    {
        $user = Auth::user();
        $this->member = Member::with(['card', 'region', 'institution'])->where('user_id', $user->id)->first();
        
        if ($this->member) {
            $this->loadFinanceData();
        }
    }

    public function loadFinanceData()
    {
        $this->dues = Due::where('member_id', $this->member->id)->orderBy('due_date', 'desc')->get();
        $this->invoices = Payment::whereHasMorph('payable', [Due::class], function($query) {
            $query->where('member_id', $this->member->id);
        })->orderBy('created_at', 'desc')->get();
    }

    public function simulatePayment(int $dueId, FinanceService $financeService)
    {
        $due = Due::where('id', $dueId)->where('member_id', $this->member?->id)->first();

        if (!$due || ($due->status->value ?? $due->status) !== 'pending') {
            return;
        }

        if ($this->selectedMethod === 'virtual_account') {
            // Simulated Virtual Account instant settlement
            $payment = $financeService->recordPayment($due, [
                'method' => \App\Support\Enums\PaymentMethod::Gateway,
                'amount' => $due->amount,
                'bank_name' => 'BNI VA Simulation',
                'gateway_ref' => 'VA-' . time(),
                'status' => \App\Support\Enums\PaymentStatus::Verified,
            ]);
            
            $invNum = $payment->invoice?->invoice_number ?? ('INV/AAI/' . date('Y') . '/VA');
            $this->paymentNotice = "Pembayaran VA Berhasil Dilunasi! Invoice resmi nomor [{$invNum}] telah diterbitkan.";
        } else {
            // Simulated Manual Bank Transfer upload
            $payment = $financeService->recordPayment($due, [
                'method' => \App\Support\Enums\PaymentMethod::BankTransfer,
                'amount' => $due->amount,
                'bank_name' => 'Mandiri Transfer Simulation',
                'payment_proof' => 'simulated_receipt_' . time() . '.pdf',
                'status' => \App\Support\Enums\PaymentStatus::Pending,
            ]);

            // Manual transfers stay under verification by national treasurer
            $due->update(['status' => 'pending']);

            $this->paymentNotice = "Bukti transfer telah disubmit! Bendahara Nasional akan segera memverifikasi dan menerbitkan invoice Anda.";
        }

        $this->loadFinanceData();
    }

    public function render()
    {
        return view('livewire.portal.member-dashboard');
    }
}
