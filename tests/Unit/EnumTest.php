<?php

namespace Tests\Unit;

use App\Support\Enums\ArticleStatus;
use App\Support\Enums\DueStatus;
use App\Support\Enums\MemberStatus;
use App\Support\Enums\OrganizationUnitType;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\PaymentStatus;
use App\Support\Enums\RegionType;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function test_member_status_returns_correct_label_and_color(): void
    {
        $this->assertSame('Aktif', MemberStatus::Active->label());
        $this->assertSame('green', MemberStatus::Active->color());
        $this->assertSame('Menunggu Verifikasi', MemberStatus::Pending->label());
        $this->assertSame('yellow', MemberStatus::Pending->color());
    }

    public function test_payment_status_returns_correct_label_and_color(): void
    {
        $this->assertSame('Terverifikasi', PaymentStatus::Verified->label());
        $this->assertSame('green', PaymentStatus::Verified->color());
        $this->assertSame('Ditolak', PaymentStatus::Rejected->label());
        $this->assertSame('red', PaymentStatus::Rejected->color());
    }

    public function test_due_status_labels_and_colors(): void
    {
        $this->assertSame('Lunas', DueStatus::Paid->label());
        $this->assertSame('green', DueStatus::Paid->color());
        $this->assertSame('Belum Dibayar', DueStatus::Pending->label());
    }

    public function test_organization_unit_type_labels(): void
    {
        $this->assertSame('Pengurus Pusat', OrganizationUnitType::Pusat->label());
        $this->assertSame('Pengurus Wilayah', OrganizationUnitType::Wilayah->label());
        $this->assertSame('Pengurus Cabang', OrganizationUnitType::Cabang->label());
    }

    public function test_region_type_labels(): void
    {
        $this->assertSame('Provinsi', RegionType::Provinsi->label());
        $this->assertSame('Kabupaten/Kota', RegionType::Kabupaten->label());
    }

    public function test_payment_methods_labels(): void
    {
        $this->assertSame('Transfer Bank', PaymentMethod::BankTransfer->label());
        $this->assertSame('Payment Gateway', PaymentMethod::Gateway->label());
        $this->assertSame('QRIS', PaymentMethod::Qris->label());
    }

    public function test_article_status_labels(): void
    {
        $this->assertSame('Dipublikasikan', ArticleStatus::Published->label());
        $this->assertSame('green', ArticleStatus::Published->color());
    }
}
