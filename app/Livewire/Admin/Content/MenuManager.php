<?php

namespace App\Livewire\Admin\Content;

use App\Domain\CMS\Models\Menu;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class MenuManager extends Component
{
    // Modal states
    public $isModalOpen = false;
    public $modalMode = 'create';
    
    // Form fields
    public $menuId;
    public $label;
    public $url;
    public $location = 'header';
    public $target = '_self';
    public $is_active = true;

    // Dynamic Linking
    public $linkType = 'custom';
    public $selectedItemId = '';

    protected $rules = [
        'label' => 'required|string|max:255',
        'url' => 'required|string|max:255',
        'location' => 'required|string|in:header,footer_media,footer_services,footer_external',
        'target' => 'required|string|in:_self,_blank',
        'is_active' => 'boolean',
    ];

    public function updatedLinkType($value)
    {
        $this->selectedItemId = '';
        if ($value === 'custom') {
            $this->url = '';
        }
    }

    public function updatedSelectedItemId($value)
    {
        if (empty($value)) return;

        if ($this->linkType === 'page') {
            $page = \App\Domain\CMS\Models\Article::find($value);
            if ($page) $this->url = '/page/' . $page->slug;
        } elseif ($this->linkType === 'article') {
            $article = \App\Domain\CMS\Models\Article::find($value);
            if ($article) $this->url = '/news/' . $article->slug;
        } elseif ($this->linkType === 'category') {
            $category = \App\Domain\CMS\Models\ArticleCategory::find($value);
            if ($category) $this->url = '/article?category=' . $category->slug;
        }
    }

    public function createMenu()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->isModalOpen = true;
    }

    public function editMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $this->menuId = $menu->id;
        $this->label = $menu->label;
        $this->url = $menu->url;
        $this->location = $menu->location;
        $this->target = $menu->target;
        $this->is_active = $menu->is_active;
        $this->linkType = 'custom';
        $this->selectedItemId = '';

        $this->modalMode = 'edit';
        $this->isModalOpen = true;
    }

    public function saveMenu()
    {
        $this->validate();

        if ($this->modalMode === 'create') {
            // Get highest order for this location
            $maxOrder = Menu::where('location', $this->location)->max('order');
            
            Menu::create([
                'label' => $this->label,
                'url' => $this->url,
                'location' => $this->location,
                'target' => $this->target,
                'is_active' => $this->is_active,
                'order' => $maxOrder ? $maxOrder + 1 : 1,
            ]);
        } else {
            $menu = Menu::findOrFail($this->menuId);
            $menu->update([
                'label' => $this->label,
                'url' => $this->url,
                'location' => $this->location,
                'target' => $this->target,
                'is_active' => $this->is_active,
            ]);
        }

        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function deleteMenu($id)
    {
        Menu::findOrFail($id)->delete();
    }

    public function moveUp($id)
    {
        $menu = Menu::findOrFail($id);
        $previousMenu = Menu::where('location', $menu->location)
            ->where('order', '<', $menu->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousMenu) {
            $currentOrder = $menu->order;
            $menu->update(['order' => $previousMenu->order]);
            $previousMenu->update(['order' => $currentOrder]);
        }
    }

    public function moveDown($id)
    {
        $menu = Menu::findOrFail($id);
        $nextMenu = Menu::where('location', $menu->location)
            ->where('order', '>', $menu->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextMenu) {
            $currentOrder = $menu->order;
            $menu->update(['order' => $nextMenu->order]);
            $nextMenu->update(['order' => $currentOrder]);
        }
    }

    private function resetForm()
    {
        $this->menuId = null;
        $this->label = '';
        $this->url = '';
        $this->location = 'header';
        $this->target = '_self';
        $this->is_active = true;
        $this->linkType = 'custom';
        $this->selectedItemId = '';
    }

    public function render()
    {
        $menus = Menu::orderBy('location')
            ->orderBy('order')
            ->get()
            ->groupBy('location');
            
        $pages = \App\Domain\CMS\Models\Article::where('type', 'page')->where('status', 'published')->orderBy('title')->get();
        $articles = \App\Domain\CMS\Models\Article::where('type', '!=', 'page')->where('status', 'published')->orderBy('title')->get();
        $categories = \App\Domain\CMS\Models\ArticleCategory::orderBy('name')->get();

        return view('livewire.admin.content.menu-manager', compact('menus', 'pages', 'articles', 'categories'));
    }
}
