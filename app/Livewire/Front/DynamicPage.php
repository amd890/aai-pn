<?php

namespace App\Livewire\Front;

use App\Domain\CMS\Models\Article;
use Livewire\Component;

class DynamicPage extends Component
{
    public $slug;
    public $page;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->page = Article::where('type', 'page')
            ->where('slug', $this->slug)
            ->where('status', 'published')
            ->first();
            
        if (!$this->page) {
            // Graceful fallback for pages not yet created in CMS
            $this->page = new Article([
                'title' => ucwords(str_replace('-', ' ', $this->slug)),
                'content' => '',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.front.dynamic-page')
            ->layout('layouts.front');
    }
}
