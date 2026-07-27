<?php

namespace Database\Seeders;

use App\Domain\Finance\Models\Due;
use App\Domain\Finance\Models\Invoice;
use App\Domain\Finance\Models\Payment;
use App\Domain\Membership\Models\Member;
use App\Support\Enums\DueStatus;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\PaymentStatus;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::where('status', 'active')->get();
        if ($members->isEmpty()) {
            return;
        }

        foreach ($members as $index => $member) {
            // 1. Create Iuran Wajib Tahunan 2024 (Lunas / Paid)
            $due2024 = Due::create([
                'member_id' => $member->id,
                'period_year' => 2024,
                'period_month' => null, // Annual fee
                'amount' => 120000, // Rp 120.000 / tahun
                'status' => DueStatus::Paid,
                'due_date' => '2024-03-31',
                'notes' => 'Iuran Keanggotaan Tahunan AAI 2024',
            ]);

            $payment = Payment::create([
                'payable_type' => Due::class,
                'payable_id' => $due2024->id,
                'member_id' => $member->id,
                'amount' => 120000,
                'method' => PaymentMethod::BankTransfer,
                'bank_name' => 'Bank Mandiri',
                'account_name' => $member->name,
                'account_number' => '123000999888' . $index,
                'status' => PaymentStatus::Verified,
                'paid_at' => '2024-02-15 10:30:00',
                'verified_by' => 1, // Super Admin
                'verified_at' => '2024-02-16 09:00:00',
                'notes' => 'Pembayaran Iuran Wajib 2024 Lunas',
            ]);

            Invoice::create([
                'payment_id' => $payment->id,
                'invoice_number' => 'INV/AAI/2024/' . str_pad($member->id, 4, '0', STR_PAD_LEFT),
                'amount' => 120000,
                'tax' => 0,
                'total' => 120000,
                'pdf_path' => null,
                'issued_at' => '2024-02-16 09:00:00',
            ]);

            // 2. Create Iuran Tahunan 2025 (Pending / Belum Bayar untuk beberapa anggota)
            if ($index % 2 == 0) {
                Due::create([
                    'member_id' => $member->id,
                    'period_year' => 2025,
                    'period_month' => null,
                    'amount' => 150000, // Rp 150.000 (penyesuaian 2025)
                    'status' => DueStatus::Pending,
                    'due_date' => '2025-03-31',
                    'notes' => 'Iuran Keanggotaan Tahunan AAI 2025',
                ]);
            } else {
                // Yang lain sudah dibayar
                $due2025 = Due::create([
                    'member_id' => $member->id,
                    'period_year' => 2025,
                    'period_month' => null,
                    'amount' => 150000,
                    'status' => DueStatus::Paid,
                    'due_date' => '2025-03-31',
                    'notes' => 'Iuran Keanggotaan Tahunan AAI 2025',
                ]);

                Payment::create([
                    'payable_type' => Due::class,
                    'payable_id' => $due2025->id,
                    'member_id' => $member->id,
                    'amount' => 150000,
                    'method' => PaymentMethod::Gateway,
                    'gateway_name' => 'Midtrans',
                    'gateway_ref' => 'MID-AAI-2025-' . uniqid(),
                    'status' => PaymentStatus::Verified,
                    'paid_at' => now()->subDays(10),
                    'verified_at' => now()->subDays(10),
                    'notes' => 'Pembayaran via Virtual Account Lunas Otomatis',
                ]);
            }
        }
    }
}
