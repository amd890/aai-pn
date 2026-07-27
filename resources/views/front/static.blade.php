@extends('layouts.front')

@section('title', $title . ' — Asosiasi Arsiparis Indonesia')
@section('meta_description', 'Halaman ' . $title . ' resmi PN-AAI.')

@section('content')
<section class="relative overflow-hidden pt-20 pb-24 lg:pt-32 lg:pb-32 bg-slate-50 dark:bg-slate-950 text-center border-b border-slate-200 dark:border-slate-800">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-amber-500/10 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h1 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight text-slate-900 dark:text-white leading-tight mb-6">
            {{ $title }}
        </h1>
        <p class="text-slate-600 dark:text-slate-300 text-lg sm:text-xl font-normal leading-relaxed max-w-3xl mx-auto">
            Halaman ini sedang dalam tahap pengembangan (Under Construction).
        </p>
    </div>
</section>

<section class="py-20 bg-white dark:bg-slate-900 min-h-[40vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose dark:prose-invert prose-amber lg:prose-lg">
        <div class="glass-card p-12 rounded-3xl text-center border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <svg class="w-16 h-16 text-slate-400 dark:text-slate-500 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Materi Segera Hadir</h2>
            <p class="text-slate-600 dark:text-slate-400">Konten untuk halaman <strong>{{ $title }}</strong> sedang disusun oleh tim Sekretariat Nasional AAI. Silakan kunjungi kembali nanti.</p>
            <a href="{{ route('front.home') }}" class="inline-block mt-8 px-6 py-2.5 rounded-lg font-bold text-sm bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>
</section>
@endsection
