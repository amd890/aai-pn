<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Finance\Models\Due;
use App\Domain\Finance\Models\Payment;
use App\Domain\Membership\Models\Member;
use App\Livewire\Admin\Correspondence\LetterManager;
use App\Livewire\Admin\Finance\PaymentVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminFinanceAndOfficeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_national_treasurer_can_verify_pending_bank_transfer_payment()
    {
        $treasurer = User::create(['name' => 'Bendahara AAI', 'email' => 'bendahara@aai.id', 'password' => 'secret']);
        $treasurer->assignRole('bendahara-nasional');

        $dummyUser = User::create(['name' => 'Pembayar Iuran', 'email' => 'pembayar@aai.id', 'password' => 'secret']);
        $member = Member::create([
            'user_id' => $dummyUser->id,
            'name' => 'Pembayar Iuran',
            'nik' => '3273000000000002',
            'status' => 'active'
        ]);

        $due = Due::create([
            'member_id' => $member->id,
            'period_year' => 2026,
            'amount' => 150000,
            'status' => 'pending'
        ]);

        $service = app(\App\Domain\Finance\Services\FinanceService::class);
        $payment = $service->recordPayment($due, [
            'amount' => 150000,
            'method' => \App\Support\Enums\PaymentMethod::BankTransfer,
            'status' => \App\Support\Enums\PaymentStatus::Pending,
        ]);

        $this->actingAs($treasurer);

        // Visit payment verification table
        $expectedDisplay = 'PAY-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
        $this->get('/admin/finance/payments')->assertStatus(200)->assertSee($expectedDisplay);

        // Execute verification in Livewire component
        Livewire::test(PaymentVerification::class)
            ->call('verifyPayment', $payment->id)
            ->assertSee("berhasil diverifikasi", false);

        $this->assertEquals('verified', ($payment->fresh()->status->value ?? $payment->fresh()->status));
        $this->assertEquals('paid', ($due->fresh()->status->value ?? $due->fresh()->status));
    }

    public function test_persuratan_eoffice_creates_outbound_letter_with_roman_month_sequence()
    {
        $secretary = User::create(['name' => 'Sekjen AAI', 'email' => 'sekjen@aai.id', 'password' => 'secret']);
        $secretary->assignRole('sekretariat-nasional');

        $this->actingAs($secretary);

        $this->get('/admin/correspondence/out')->assertStatus(200)->assertSee('Tata Naskah Dinas', false);

        Livewire::test(LetterManager::class)
            ->set('type_code', 'UND')
            ->set('recipient', 'Kepala Arsip Nasional Republik Indonesia (ANRI)')
            ->set('subject', 'Undangan Rapat Kerja Nasional AAI 2026')
            ->set('content', 'Dengan rasa hormat, kami mengundang Bapak/Ibu pada Rakornas yang diselenggarakan di Hotel Indonesia...')
            ->call('createLetter')
            ->assertSee("berhasil diterbitkan dengan nomor resmi:", false);

        $year = date('Y');
        $this->assertDatabaseHas('letters_out', [
            'recipient' => 'Kepala Arsip Nasional Republik Indonesia (ANRI)',
            'subject' => 'Undangan Rapat Kerja Nasional AAI 2026'
        ]);

        $letter = \App\Domain\Correspondence\Models\LetterOut::first();
        $this->assertStringContainsString("001/UND/AAI-PUSAT/", $letter->letter_number);
        $this->assertStringContainsString((string)$year, $letter->letter_number);
        $this->assertNotEmpty($letter->qr_code);
    }
}
