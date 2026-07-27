<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\CMS\Models\Agenda;
use App\Domain\CMS\Models\Article;
use App\Domain\CMS\Models\ArticleCategory;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\OrganizationMember;
use App\Domain\Organization\Models\OrganizationPeriod;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Domain\Organization\Models\Region;
use App\Support\Enums\ArticleStatus;
use App\Support\Enums\ArticleType;
use App\Support\Enums\OrganizationUnitType;
use App\Support\Enums\RegionType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationAndContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_unit_hierarchy_and_period_members(): void
    {
        $region = Region::create(['name' => 'DKI Jakarta', 'code' => '31', 'type' => RegionType::Provinsi]);
        
        $pusat = OrganizationUnit::create([
            'name' => 'Pengurus Nasional AAI',
            'type' => OrganizationUnitType::Pusat,
            'code' => 'AAI-PUSAT',
            'region_id' => $region->id,
            'status' => 'active',
        ]);

        $wilayah = OrganizationUnit::create([
            'name' => 'Pengurus Wilayah DKI Jakarta',
            'type' => OrganizationUnitType::Wilayah,
            'code' => 'AAI-WIL-DKI',
            'region_id' => $region->id,
            'parent_id' => $pusat->id,
            'status' => 'active',
        ]);

        $this->assertSame($pusat->id, $wilayah->parent->id);
        $this->assertCount(1, $pusat->children);

        $period = OrganizationPeriod::create([
            'organization_unit_id' => $pusat->id,
            'period_name' => 'Periode 2024-2028',
            'start_year' => 2024,
            'end_year' => 2028,
            'status' => 'active',
        ]);

        $user = User::factory()->create();
        $member = Member::create(['user_id' => $user->id, 'name' => 'Ketum Test']);

        $orgMember = OrganizationMember::create([
            'organization_period_id' => $period->id,
            'member_id' => $member->id,
            'position' => 'Ketua Umum',
            'position_category' => 'Pimpinan',
            'status' => 'active',
        ]);

        $this->assertSame('Ketua Umum', $period->organizationMembers->first()->position);
        $this->assertSame('Pengurus Nasional AAI', $period->organizationUnit->name);
    }

    public function test_article_publishing_and_agenda_scoping(): void
    {
        $category = ArticleCategory::create([
            'name' => 'Berita Utama',
            'slug' => 'berita-utama',
            'is_active' => true,
        ]);

        $author = User::factory()->create();

        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'Rakernas AAI 2024 Dibuka',
            'slug' => 'rakernas-aai-2024-dibuka',
            'content' => 'Isi berita selengkapnya...',
            'type' => ArticleType::News,
            'status' => ArticleStatus::Published,
            'published_at' => now()->subHour(),
            'author_id' => $author->id,
        ]);

        $this->assertSame('Berita Utama', $article->category->name);
        $this->assertSame(1, Article::published()->count());

        Agenda::create([
            'title' => 'Webinar Kearsipan Digital',
            'slug' => 'webinar-kearsipan-digital',
            'description' => 'Webinar bersertifikat',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(5)->addHours(3),
            'status' => 'upcoming',
        ]);

        $this->assertSame(1, Agenda::upcoming()->count());
    }
}
