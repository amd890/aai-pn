<?php

namespace App\Livewire\Admin\Content;

use App\Domain\CMS\Models\Article;
use App\Domain\CMS\Models\ArticleCategory;
use App\Domain\CMS\Repositories\CmsRepository;
use App\Domain\CMS\Services\CacheService;
use App\Support\Enums\ArticleStatus;
use App\Support\Enums\ArticleType;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class PageManager extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showFormModal = false;
    public bool $isEditMode = false;
    public ?int $articleId = null;

    public string $title = '';
    public string $excerpt = '';
    public string $content = '';
    public string $type = 'page'; // default
    public string $status = 'draft';
    public $cover_image; // for file upload

    public string $noticeMessage = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'excerpt' => 'required|string|max:500',
        'content' => 'required|string',
        'type' => 'required|string',
        'status' => 'required|string',
        'cover_image' => 'nullable|image|max:2048',
    ];

    public function createNew()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showFormModal = true;
    }

    public function edit(int $id)
    {
        $this->resetForm();
        $article = Article::findOrFail($id);
        
        $this->articleId = $article->id;
        $this->title = $article->title;
        $this->excerpt = $article->excerpt ?? '';
        $this->content = $article->content;
        $this->type = $article->type->value;
        $this->status = $article->status->value;
        
        $this->isEditMode = true;
        $this->showFormModal = true;
    }

    public function save(CmsRepository $repo)
    {
        $this->validate();

        $category = ArticleCategory::firstOrCreate(
            ['slug' => 'umum'],
            ['name' => 'Umum', 'description' => 'Kategori Umum']
        );

        $data = [
            'category_id' => $category->id,
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . time(),
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'type' => ArticleType::tryFrom($this->type) ?? ArticleType::Page,
            'status' => ArticleStatus::tryFrom($this->status) ?? ArticleStatus::Draft,
            'author_id' => auth()->id() ?? 1,
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->isEditMode) {
            $article = Article::findOrFail($this->articleId);
            // Don't change slug on edit if not strictly needed, or update if title changed.
            // Keeping it simple for now.
            unset($data['slug']);
            $article->update($data);
            $this->noticeMessage = "Halaman berhasil diperbarui!";
        } else {
            $article = Article::create($data);
            $this->noticeMessage = "Halaman berhasil diterbitkan!";
        }

        if ($this->cover_image) {
            $article->addMedia($this->cover_image->getRealPath())
                    ->usingName($this->cover_image->getClientOriginalName())
                    ->toMediaCollection('featured_image');
        }

        $this->showFormModal = false;
        $this->resetForm();
        CacheService::flushPages($article->slug);
        CacheService::flushSitemap();
    }

    public function delete(int $id)
    {
        $article = Article::findOrFail($id);
        $slug = $article->slug;
        $article->delete();
        $this->noticeMessage = "Halaman dihapus!";
        CacheService::flushPages($slug);
        CacheService::flushSitemap();
    }

    public function resetForm()
    {
        $this->reset(['title', 'excerpt', 'content', 'type', 'status', 'cover_image', 'articleId']);
        $this->resetValidation();
    }

    public function render()
    {
        $pages = Article::with(['category', 'author'])
            ->where('type', 'page')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.content.page-manager', [
            'pages' => $pages
        ]);
    }
}
