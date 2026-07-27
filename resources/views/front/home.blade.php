@extends('layouts.front')

@section('title', 'Asosiasi Arsiparis Indonesia — PN-AAI')
@section('meta_description', 'PN-AAI menyatukan para profesional dan institusi di sektor arsip. Arsip penting agar setiap orang dapat memiliki akses terhadap informasi.')

@section('content')
<!-- Minimalist Hero -->
<section class="relative pt-24 pb-12 lg:pt-32 lg:pb-16 bg-slate-50 dark:bg-slate-950 text-center px-4 transition-colors duration-300">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 dark:opacity-[0.03]"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-2xl h-[300px] bg-amber-500/10 blur-[120px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto relative z-10">
        <h1 class="font-heading font-black text-4xl sm:text-5xl lg:text-7xl tracking-tighter text-slate-900 dark:text-white leading-[1.1] mb-6">
            Menyatukan Profesional <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-amber-600 dark:from-amber-400 dark:to-yellow-600">Sektor Arsip Indonesia</span>
        </h1>
        <p class="text-slate-600 dark:text-slate-400 text-lg sm:text-xl font-normal leading-relaxed max-w-2xl mx-auto">
            Arsip adalah jejak peradaban. Kami memastikan setiap orang memiliki akses informasi untuk merekonstruksi masa lalu, hari ini, dan masa depan.
        </p>
    </div>
</section>

<!-- Magazine Bento Grid Layout -->
<section class="py-12 bg-slate-50 dark:bg-slate-950 px-4 sm:px-6 lg:px-8 max-w-[90rem] mx-auto mb-20 transition-colors duration-300">
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 auto-rows-[minmax(180px,auto)] gap-4 lg:gap-6">
        
        <!-- Bento 1: About (Large Span) -->
        <div class="md:col-span-2 lg:col-span-2 md:row-span-2 glass-card rounded-3xl p-8 lg:p-10 relative overflow-hidden group border-slate-200 dark:border-slate-800/80 hover:border-amber-500/30">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent mix-blend-overlay"></div>
            <!-- Decorative Image Placeholder Background -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-slate-200/50 dark:bg-slate-800/50 rounded-bl-full -mr-20 -mt-20 border-l border-b border-slate-300/50 dark:border-slate-700/50"></div>
            
            <div class="relative z-10 flex flex-col h-full justify-between space-y-6">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-widest mb-4 border border-amber-500/20">Tentang AAI</span>
                    <h2 class="font-heading font-bold text-3xl sm:text-4xl text-slate-900 dark:text-white leading-tight mb-4 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Pengurus Nasional <br>Asosiasi Arsiparis</h2>
                    <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base leading-relaxed max-w-md">
                        Dideklarasikan pada 14 Agustus 1998, mewadahi komunitas profesional kearsipan ASN maupun swasta dari Sabang sampai Merauke.
                    </p>
                </div>
                <div class="mt-auto pt-8">
                    <a href="{{ route('front.about') }}" class="inline-flex items-center gap-2 font-bold text-sm text-slate-900 dark:text-white hover:text-amber-600 dark:hover:text-amber-400 transition-colors group/link">
                        Baca Selengkapnya 
                        <span class="bg-slate-200 dark:bg-slate-800 group-hover/link:bg-amber-500 text-amber-600 dark:text-amber-400 group-hover/link:text-white dark:group-hover:text-slate-950 w-6 h-6 rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bento 2: Pertemuan -->
        <div class="glass-card rounded-3xl p-6 relative overflow-hidden border-slate-200 dark:border-slate-800/80 hover:border-blue-500/30 flex flex-col justify-between">
            <div class="w-10 h-10 rounded-full bg-blue-500/10 text-blue-500 dark:text-blue-400 flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-2">Pertemuan</h3>
                <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">Berpartisipasi dalam diskusi dan kolaborasi antar profesional.</p>
            </div>
        </div>

        <!-- Bento 3: Profesionalisasi -->
        <div class="glass-card rounded-3xl p-6 relative overflow-hidden border-slate-200 dark:border-slate-800/80 hover:border-emerald-500/30 flex flex-col justify-between">
            <div class="w-10 h-10 rounded-full bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-2">Profesionalisasi</h3>
                <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">Peningkatan kompetensi melalui pembelajaran seumur hidup.</p>
            </div>
        </div>

        <!-- Bento 4: Meneliti -->
        <div class="glass-card rounded-3xl p-6 relative overflow-hidden border-slate-200 dark:border-slate-800/80 hover:border-purple-500/30 flex flex-col justify-between">
            <div class="w-10 h-10 rounded-full bg-purple-500/10 text-purple-500 dark:text-purple-400 flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-2">Riset</h3>
                <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">Kajian dampak regulasi baru terhadap tata kelola dan privasi arsip.</p>
            </div>
        </div>

        <!-- Bento 5: Lobi -->
        <div class="glass-card rounded-3xl p-6 relative overflow-hidden border-slate-200 dark:border-slate-800/80 hover:border-rose-500/30 flex flex-col justify-between">
            <div class="w-10 h-10 rounded-full bg-rose-500/10 text-rose-500 dark:text-rose-400 flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-2">Advokasi & Lobi</h3>
                <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">Memantau dan memberikan pengaruh pada UU kearsipan.</p>
            </div>
        </div>

        <!-- Bento 6: Membership CTA (Wide Span) -->
        <div class="md:col-span-3 lg:col-span-4 md:row-span-1 bg-gradient-to-r from-amber-500 to-amber-600 rounded-3xl p-8 sm:p-10 text-slate-950 flex flex-col lg:flex-row items-center justify-between gap-8 shadow-xl shadow-amber-500/10">
            <div class="flex-1">
                <h2 class="font-heading font-black text-2xl sm:text-3xl mb-3 text-slate-900 dark:text-slate-950">Jadilah Bagian dari Jaringan Kami</h2>
                <p class="font-medium opacity-90 max-w-2xl mb-6 text-slate-800 dark:text-slate-900">
                    Mulai dari buletin eksklusif, sesi pertemuan rutin, jurnal gratis, hingga sertifikasi yang diakui.
                </p>
                <div class="flex flex-wrap gap-2 sm:gap-4 font-bold text-xs text-slate-800 dark:text-slate-900">
                    <span class="px-3 py-1.5 bg-black/10 rounded-lg">✅ Jaringan Luas</span>
                    <span class="px-3 py-1.5 bg-black/10 rounded-lg">✅ Buletin 6x Setahun</span>
                    <span class="px-3 py-1.5 bg-black/10 rounded-lg">✅ Dokumen Bantuan</span>
                </div>
            </div>
            <div class="w-full lg:w-auto shrink-0 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('front.membership.register') }}" class="px-8 py-4 bg-slate-900 dark:bg-slate-950 hover:bg-slate-800 dark:hover:bg-slate-900 text-amber-500 dark:text-amber-400 font-bold text-sm text-center rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                    Daftar Sekarang
                </a>
                <a href="{{ route('front.membership.verify') }}" class="px-8 py-4 bg-black/10 hover:bg-black/20 border border-black/20 text-slate-900 dark:text-slate-950 font-bold text-sm text-center rounded-xl transition-all">
                    Cek KTA Saya
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
