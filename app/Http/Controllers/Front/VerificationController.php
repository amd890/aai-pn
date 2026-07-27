<?php

namespace App\Http\Controllers\Front;

use App\Domain\LSP\Repositories\LspRepository;
use App\Domain\Membership\Repositories\MembershipRepository;
use App\Domain\Membership\Services\MembershipService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function __construct(
        protected MembershipRepository $membershipRepository,
        protected MembershipService $membershipService,
        protected LspRepository $lspRepository
    ) {
    }

    /**
     * Display public membership & KTA verification search portal.
     */
    public function membership(Request $request): View
    {
        $query = trim((string) $request->get('q'));
        $member = null;
        $card = null;

        if ($query !== '') {
            // Check by card number or QR first
            $card = $this->membershipRepository->findByCardNumberOrQr($query);
            if ($card && $card->member) {
                $member = $card->member;
            } else {
                // Otherwise lookup by Member Number
                $member = $this->membershipRepository->findByMemberNumber($query);
                if ($member && $member->card) {
                    $card = $member->card;
                }
            }
        }

        // Mask privacy sensitive fields
        if ($member) {
            $member->masked_nik = $this->membershipService->maskPersonalData($member->nik, 4, 3);
            $member->masked_nip = $this->membershipService->maskPersonalData($member->nip, 4, 3);
            $member->masked_phone = $this->membershipService->maskPersonalData($member->phone, 4, 3);
        }

        return view('front.verification.membership', compact('member', 'card', 'query'));
    }

    /**
     * Direct QR card scanning lookup endpoint.
     */
    public function card(Request $request): View
    {
        $query = trim((string) $request->get('number', $request->get('q')));
        return $this->membership($request->merge(['q' => $query]));
    }

    /**
     * Display LSP Competency Certificate verification portal.
     */
    public function certification(Request $request): View
    {
        $query = trim((string) $request->get('q', $request->get('cert_number')));
        $certificate = null;

        if ($query !== '') {
            $certificate = $this->lspRepository->findByCertificateNumberOrQr($query);
        }

        return view('front.verification.certification', compact('certificate', 'query'));
    }
}
