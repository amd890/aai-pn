@extends('layouts.front')

@section('title', 'Portal Berita & Edukasi Kearsipan — Asosiasi Arsiparis Indonesia')
@section('meta_description', 'Kumpulan berita kegiatan AAI, artikel ilmiah Kearsipan Digital, regulasi kearsipan digital, dan transformasi memori nasional.')

@section('content')
<section class="pt-12 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-8 mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-500">Publikasi & Edukasi</span>
            <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-slate-900 dark:text-white mt-1">
                Berita & Artikel Kearsipan
            </h1>
            <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg mt-2">
                Informasi resmi, kajian ilmu kearsipan, dan agenda kegiatan AAI Nasional & Wilayah.
            </p>
        </div>

        <!-- Search Form -->
        <form action="{{ route('front.news.index') }}" method="GET" class="flex w-full md:w-80 shadow-sm">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari artikel atau topik..." 
                   class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-l-xl text-sm text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500">
            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 dark:hover:bg-amber-400 text-white dark:text-slate-950 font-bold rounded-r-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </form>
    </div>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($articles as $article)
        <article class="glass-card rounded-2xl p-6 flex flex-col justify-between border border-slate-200 dark:border-slate-800/80 hover:border-amber-400 dark:hover:border-amber-500/40 transition duration-300">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-3">
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold border border-amber-500/20">
                        {{ $article->category->name ?? 'Informasi' }}
                    </span>
                    <span>&bull;</span>
                    <time datetime="{{ $article->published_at?->toIso8601String() }}">
                        {{ $article->published_at?->format('d M Y') ?? 'Baru' }}
                    </time>
                </div>

                <h2 class="font-heading font-bold text-xl text-slate-900 dark:text-white hover:text-amber-600 dark:hover:text-amber-400 transition-colors line-clamp-2 mb-3">
                    <a href="{{ route('front.news.show', $article->slug) }}">{{ $article->title }}</a>
                </h2>

                <p class="text-slate-600 dark:text-slate-400 text-sm line-clamp-3 mb-6 leading-relaxed">
                    {{ $article->excerpt ?: Str::limit(strip_tags($article->content), 130) }}
                </p>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $article->author->name ?? 'Admin AAI' }}</span>
                <a href="{{ route('front.news.show', $article->slug) }}" class="font-bold text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1">
                    Baca Selengkapnya &rarr;
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-20 bg-white/50 dark:bg-slate-900/50 rounded-3xl border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-medium">
            Tidak ditemukan artikel yang sesuai dengan pencarian Anda.
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
    <div class="mt-16 flex justify-center">
        {{ $articles->withQueryString()->links() }}
    </div>
    @endif
</section>
@endsection
