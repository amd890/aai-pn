<?php

namespace App\Domain\Membership\Services;

use App\Domain\Auth\Models\User;
use App\Domain\Finance\Services\FinanceService;
use App\Domain\Membership\Models\Member;
use App\Domain\Membership\Models\MemberCard;
use App\Domain\Membership\Models\MemberStatusHistory;
use App\Domain\Membership\Repositories\MembershipRepository;
use App\Support\Enums\MemberStatus;
use App\Support\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MembershipService extends BaseService
{
    public function __construct(
        protected MembershipRepository $membershipRepository,
        protected FinanceService $financeService
    ) {
    }

    /**
     * Register a new member and corresponding user account with Pending status.
     */
    public function registerMember(array $userData, array $memberData): Member
    {
        return $this->transactional(function () use ($userData, $memberData) {
            $user = User::create([
                'name' => $userData['name'] ?? $memberData['name'],
                'email' => $userData['email'],
                'username' => $userData['username'] ?? Str::slug($userData['email']),
                'password' => Hash::make($userData['password'] ?? Str::random(12)),
                'status' => 'active',
            ]);

            // Assign standard member role if available
            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('anggota');
                } catch (\Exception $e) {
                    // Fallback if role is unseeded in specific unit test context
                }
            }

            $member = Member::create(array_merge($memberData, [
                'user_id' => $user->id,
                'status' => MemberStatus::Pending,
                'member_number' => null,
                'registered_at' => now(),
            ]));

            MemberStatusHistory::create([
                'member_id' => $member->id,
                'from_status' => null,
                'to_status' => MemberStatus::Pending->value,
                'reason' => 'Pendaftaran Baru via Portal Kearsipan',
                'changed_by' => $user->id,
                'changed_at' => now(),
            ]);

            return $member->fresh(['user', 'institution', 'region']);
        }, 'Failed to register new member');
    }

    /**
     * Approve member registration, generate official Member Number and digital KTA card, and issue initial dues.
     */
    public function approveMember(Member $member, User $verifier): Member
    {
        return $this->transactional(function () use ($member, $verifier) {
            $oldStatus = $member->status?->value ?? 'pending';

            if (empty($member->member_number)) {
                $member->member_number = $this->generateMemberNumber($member);
            }

            $member->update([
                'status' => MemberStatus::Active,
                'approved_at' => now(),
                'approved_by' => $verifier->id,
            ]);

            MemberStatusHistory::create([
                'member_id' => $member->id,
                'from_status' => $oldStatus,
                'to_status' => MemberStatus::Active->value,
                'reason' => 'Verifikasi dokumen disetujui oleh verifikator AAI',
                'changed_by' => $verifier->id,
                'changed_at' => now(),
            ]);

            // Issue digital KTA card
            $cardNumber = 'CARD-' . str_replace('.', '-', $member->member_number);
            $qrCode = 'QR-AAI-' . md5($member->id . $member->member_number . now());

            MemberCard::create([
                'member_id' => $member->id,
                'card_number' => $cardNumber,
                'qr_code' => $qrCode,
                'qr_data' => json_encode([
                    'member_number' => $member->member_number,
                    'name' => $member->name,
                    'verified' => true,
                ]),
                'template' => 'standard_gold_2024',
                'issued_at' => now(),
                'expired_at' => now()->addYears(3),
                'status' => 'active',
            ]);

            // Issue annual mandatory dues
            $this->financeService->generateInitialDues($member);

            return $member->fresh(['card', 'dues', 'institution', 'region']);
        }, 'Failed to approve member');
    }

    /**
     * Generate structured member number: AAI.{ProvCode}.{Year}.{Seq}
     */
    protected function generateMemberNumber(Member $member): string
    {
        $provCode = str_pad((string) ($member->region?->code ?? '00'), 2, '0', STR_PAD_LEFT);
        $year = date('Y');
        $prefix = "AAI.{$provCode}.{$year}.";

        $latestMember = Member::where('member_number', 'like', "{$prefix}%")
            ->latest('id')
            ->value('member_number');

        $sequence = 1;
        if ($latestMember) {
            $parts = explode('.', $latestMember);
            $sequence = ((int) end($parts)) + 1;
        }

        $formattedSeq = str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$formattedSeq}";
    }

    /**
     * Mask personal data (NIK/Nip/Phone) for public verification privacy.
     */
    public function maskPersonalData(?string $value, int $visibleStart = 4, int $visibleEnd = 4): string
    {
        if (empty($value)) {
            return '-';
        }
        
        $len = strlen($value);
        if ($len <= ($visibleStart + $visibleEnd)) {
            return str_repeat('X', max($len, 6));
        }

        $start = substr($value, 0, $visibleStart);
        $end = substr($value, -$visibleEnd);
        $mask = str_repeat('X', $len - ($visibleStart + $visibleEnd));

        return "{$start}{$mask}{$end}";
    }
}
