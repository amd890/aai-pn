@extends('layouts.front')

@section('title', $article->title . ' — Asosiasi Arsiparis Indonesia')
@section('meta_description', $article->seo_description ?: Str::limit(strip_tags($article->content), 160))

@section('content')
<article class="pt-12 pb-24 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('front.home') }}" class="hover:text-amber-600 dark:hover:text-amber-400">Beranda</a>
        <span>/</span>
        <a href="{{ route('front.news.index') }}" class="hover:text-amber-600 dark:hover:text-amber-400">Berita & Kearsipan Digital</a>
        <span>/</span>
        <span class="text-amber-600 dark:text-amber-400 truncate max-w-xs">{{ $article->category->name ?? 'Publikasi' }}</span>
    </nav>

    <!-- Header (SEO h1) -->
    <header class="border-b border-slate-200 dark:border-slate-800/80 pb-8 mb-10">
        <div class="flex items-center gap-3 text-xs text-amber-600 dark:text-amber-400 font-semibold mb-3">
            <span class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20">
                {{ $article->category->name ?? 'Informasi Internal' }}
            </span>
            <span>&bull;</span>
            <time datetime="{{ $article->published_at?->toIso8601String() }}">
                {{ $article->published_at?->format('l, d M Y - H:i') ?? 'Publikasi' }} WIB
            </time>
        </div>

        <h1 class="font-heading font-black text-3xl sm:text-5xl text-slate-900 dark:text-white leading-tight mb-6">
            {{ $article->title }}
        </h1>

        <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-400">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center font-heading font-extrabold text-white dark:text-slate-950">
                    {{ substr($article->author->name ?? 'A', 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-slate-900 dark:text-white">{{ $article->author->name ?? 'Redaksi AAI Nasional' }}</div>
                    <div class="text-xs text-slate-500">Kontributor Kearsipan Digital</div>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs bg-slate-100 dark:bg-slate-900/80 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                {{ number_format($article->view_count + 1) }} Kali Dibaca
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <div class="prose dark:prose-invert prose-amber max-w-none text-slate-700 dark:text-slate-200 text-base sm:text-lg leading-relaxed space-y-6">
        {!! nl2br(e($article->content)) !!}
    </div>

    <!-- Social Share & Footer -->
    <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">Bagikan Publikasi Ini:</span>
        <div class="flex items-center space-x-4">
            <a href="#" class="px-4 py-2 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs border border-slate-300 dark:border-slate-700 transition">Copy Link</a>
            <a href="{{ route('front.news.index') }}" class="text-amber-600 dark:text-amber-400 hover:underline font-bold text-sm">&larr; Kembali ke Daftar Berita</a>
        </div>
    </div>
</article>

<!-- Related Articles -->
@if($relatedArticles->count() > 0)
<section class="py-16 bg-slate-50 dark:bg-slate-900/60 border-t border-slate-200 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-heading font-extrabold text-2xl text-slate-900 dark:text-white mb-8 text-center">Artikel Kearsipan Terkait</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedArticles as $rel)
            <div class="glass-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-transparent">
                <time class="text-xs text-amber-600 dark:text-amber-500 font-medium">{{ $rel->published_at?->format('d M Y') }}</time>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mt-1 line-clamp-2">
                    <a href="{{ route('front.news.show', $rel->slug) }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">{{ $rel->title }}</a>
                </h3>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
