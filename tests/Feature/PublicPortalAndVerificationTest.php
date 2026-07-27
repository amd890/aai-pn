<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPortalAndVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Run database seeding to ensure real sample data exists for portal presentation
        $this->seed();
    }

    public function test_home_page_displays_live_statistical_counters_and_content_feeds()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Asosiasi Arsiparis Indonesia', false);
        $response->assertSee('Menyatukan Profesional', false);
        $response->assertSee('Jadilah Bagian dari Jaringan Kami', false);
    }

    public function test_news_index_and_article_detail_page_with_seo_and_view_increment()
    {
        // Visit news portal
        $response = $this->get('/news');
        $response->assertStatus(200);
        $response->assertSee('Berita & Artikel Kearsipan', false);

        // Locate seeded article
        $article = \App\Domain\CMS\Models\Article::where('status', 'published')->first();
        $this->assertNotNull($article);

        $initialViewCount = $article->view_count;

        // Visit article detail
        $detailResponse = $this->get('/news/' . $article->slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($article->title, false);
        $detailResponse->assertSee('Kali Dibaca', false);

        // Confirm view count incremented atomically
        $this->assertEquals($initialViewCount + 1, $article->fresh()->view_count);
    }

    public function test_public_membership_and_kta_verification_with_privacy_masking()
    {
        // Visit empty verification page
        $response = $this->get('/membership/verify');
        $response->assertStatus(200);
        $response->assertSee('Verifikasi Anggota & KTA Digital', false);

        // Locate seeded active member card
        $card = \App\Domain\Membership\Models\MemberCard::with('member')->first();
        $this->assertNotNull($card);
        
        $memberNumber = $card->member->member_number;

        // Query verification endpoint by member number
        $searchResponse = $this->get("/membership/verify?q={$memberNumber}");
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('TERVERIFIKASI RESMI (ACTIVE)', false);
        $searchResponse->assertSee($memberNumber, false);
        $searchResponse->assertSee('Kartu Tanda Anggota Digital', false);

        // Confirm NIK masking privacy is enforced on HTML representation
        $searchResponse->assertSee('XXXX', false);
    }

    public function test_public_lsp_competency_certification_verification()
    {
        $cert = \App\Domain\LSP\Models\LspCertificate::first();
        $this->assertNotNull($cert);

        $response = $this->get("/certification/verify?q={$cert->certificate_number}");
        $response->assertStatus(200);
        $response->assertSee('KOMPETEN / CERTIFIED', false);
        $response->assertSee($cert->certificate_number, false);
        $response->assertSee('Skema Sertifikasi Profesi', false);
    }
}
