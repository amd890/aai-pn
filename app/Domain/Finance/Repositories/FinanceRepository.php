<?php

namespace App\Domain\Finance\Repositories;

use App\Domain\Finance\Models\Due;
use App\Domain\Finance\Models\Invoice;
use App\Domain\Finance\Models\Payment;
use App\Support\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FinanceRepository extends EloquentRepository
{
    public function __construct(Due $model)
    {
        parent::__construct($model);
    }

    public function getPendingDuesForMember(int $memberId): LengthAwarePaginator
    {
        return $this->query()
            ->where('member_id', $memberId)
            ->where('status', 'pending')
            ->orderBy('due_date', 'asc')
            ->paginate(15);
    }

    public function findPaymentByGatewayRef(string $gatewayRef): ?Payment
    {
        return Payment::with(['payable', 'member'])
            ->where('gateway_ref', $gatewayRef)
            ->first();
    }

    public function getInvoicesByMember(int $memberId, int $perPage = 15): LengthAwarePaginator
    {
        return Invoice::with(['payment.payable'])
            ->whereHas('payment', fn ($query) => $query->where('member_id', $memberId))
            ->latest('issued_at')
            ->paginate($perPage);
    }

    public function getLatestInvoiceNumberForYear(int $year): ?string
    {
        return Invoice::whereYear('issued_at', $year)
            ->where('invoice_number', 'like', "INV/AAI/{$year}/%")
            ->latest('id')
            ->value('invoice_number');
    }
}
