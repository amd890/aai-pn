<?php

namespace App\Domain\Membership\Repositories;

use App\Domain\Membership\Models\Member;
use App\Domain\Membership\Models\MemberCard;
use App\Support\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class MembershipRepository extends EloquentRepository
{
    public function __construct(Member $model)
    {
        parent::__construct($model);
    }

    public function findByMemberNumber(string $memberNumber): ?Member
    {
        return $this->query()
            ->with(['institution', 'region', 'card', 'user'])
            ->where('member_number', $memberNumber)
            ->first();
    }

    public function findByCardNumberOrQr(string $identifier): ?MemberCard
    {
        return MemberCard::with(['member.institution', 'member.region'])
            ->where(function (Builder $query) use ($identifier) {
                $query->where('card_number', $identifier)
                      ->orWhere('qr_code', $identifier);
            })
            ->first();
    }

    public function paginateVerifiedMembers(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()
            ->with(['institution', 'region'])
            ->active();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }

        if (!empty($filters['jenjang'])) {
            $query->where('jenjang_arsiparis', $filters['jenjang']);
        }

        return $query->latest('approved_at')->paginate($perPage);
    }
}
