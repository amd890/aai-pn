<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\CMS\Models\Article;
use App\Livewire\Admin\Content\ArticleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CmsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_admin_can_manage_articles()
    {
        $admin = User::create(['name' => 'Admin CMS', 'email' => 'cms@aai.id', 'password' => 'secret']);

        $this->actingAs($admin);

        // Test creating article
        Livewire::test(ArticleManager::class)
            ->set('title', 'Masa Depan Kearsipan Digital di Indonesia')
            ->set('excerpt', 'Ringkasan masa depan Kearsipan Digital...')
            ->set('content', 'Isi lengkap artikel Kearsipan Digital...')
            ->set('type', 'article')
            ->set('status', 'published')
            ->call('save')
            ->assertSee('Artikel berhasil diterbitkan');

        $this->assertDatabaseHas('articles', [
            'title' => 'Masa Depan Kearsipan Digital di Indonesia',
            'type' => 'article',
            'status' => 'published',
        ]);

        $article = Article::first();

        // Test editing article
        Livewire::test(ArticleManager::class)
            ->call('edit', $article->id)
            ->set('title', 'Masa Depan Kearsipan Digital di Indonesia (Revisi)')
            ->call('save')
            ->assertSee('Artikel berhasil diperbarui');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Masa Depan Kearsipan Digital di Indonesia (Revisi)',
        ]);

        // Test deleting article
        Livewire::test(ArticleManager::class)
            ->call('delete', $article->id)
            ->assertSee('Artikel dihapus');

        $this->assertSoftDeleted('articles', ['id' => $article->id]);
    }
}
