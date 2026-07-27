<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Membership\Models\Member;
use App\Domain\Membership\Models\MemberCard;
use App\Domain\Membership\Models\MemberDocument;
use App\Domain\Organization\Models\Institution;
use App\Domain\Organization\Models\Region;
use App\Support\Enums\MemberStatus;
use App\Support\Enums\RegionType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_creation_with_relationships_and_scopes(): void
    {
        $user = User::factory()->create(['name' => 'Sari Member', 'username' => 'sari.m']);

        $region = Region::create([
            'name' => 'Jawa Barat',
            'code' => '32',
            'type' => RegionType::Provinsi,
            'is_active' => true,
        ]);

        $institution = Institution::create([
            'name' => 'Dinas Kearsipan Jabar',
            'type' => 'Pemerintah Daerah',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'is_active' => true,
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'member_number' => 'AAI-TEST-0001',
            'nik' => '3273000000000001',
            'name' => $user->name,
            'gender' => 'P',
            'institution_id' => $institution->id,
            'position' => 'Arsiparis Muda',
            'jenjang_arsiparis' => 'Ahli Muda',
            'golongan' => 'III/c',
            'education' => 'S1 Kearsipan',
            'status' => MemberStatus::Active,
            'region_id' => $region->id,
            'registered_at' => now(),
        ]);

        // Assert relationships
        $this->assertSame($user->id, $member->user->id);
        $this->assertSame('Dinas Kearsipan Jabar', $member->institution->name);
        $this->assertSame('Jawa Barat', $member->region->name);

        // Assert active scopes
        $this->assertSame(1, Member::active()->count());
        $this->assertSame(0, Member::pending()->count());
    }

    public function test_member_card_and_document_attachments(): void
    {
        $user = User::factory()->create();
        $member = Member::create([
            'user_id' => $user->id,
            'name' => 'Budi Arsiparis',
            'status' => MemberStatus::Active,
        ]);

        $card = MemberCard::create([
            'member_id' => $member->id,
            'card_number' => 'CARD-999',
            'qr_code' => 'QR-999',
            'template' => 'standard',
            'issued_at' => now(),
            'expired_at' => now()->addYears(2),
            'status' => 'active',
        ]);

        $doc = MemberDocument::create([
            'member_id' => $member->id,
            'type' => 'ijazah',
            'name' => 'Ijazah S1 Kearsipan',
            'file_path' => 'docs/ijazah.pdf',
            'verified' => true,
        ]);

        $this->assertTrue($card->isActive());
        $this->assertSame($card->id, $member->card->id);
        $this->assertCount(1, $member->documents);
        $this->assertSame('Ijazah S1 Kearsipan', $member->documents->first()->name);
    }
}
