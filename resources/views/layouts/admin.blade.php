<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Executive Admin Dashboard — Asosiasi Arsiparis Indonesia')</title>
    
    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        .font-heading { font-family: 'Outfit', sans-serif; }
        .glass-admin {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .glass-admin {
            background: rgba(15, 23, 42, 0.7);
            border-right: 1px solid rgba(245, 158, 11, 0.1);
        }
        .nav-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-item:hover { transform: translateX(4px); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans antialiased selection:bg-amber-500 selection:text-white min-h-screen flex flex-col sm:flex-row relative overflow-x-hidden transition-colors duration-300">
    
    <!-- Premium Ambient Gradients -->
    <div class="fixed inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-0 dark:opacity-[0.03] pointer-events-none z-0"></div>
    <div class="fixed top-[-15%] right-[-5%] w-[60vw] h-[60vw] rounded-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-aai-blue/5 dark:from-aai-blue/25 via-slate-50/0 dark:via-slate-950/0 to-transparent blur-[130px] pointer-events-none z-0 transition-colors duration-500"></div>
    <div class="fixed bottom-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-aai-orange/10 dark:from-aai-orange/15 via-slate-50/0 dark:via-slate-950/0 to-transparent blur-[120px] pointer-events-none z-0 transition-colors duration-500"></div>

    <!-- Enterprise Sidebar -->
    <aside class="w-full sm:w-72 glass-admin shrink-0 flex flex-col justify-between p-6 relative z-20 shadow-2xl shadow-black/50">
        <div>
            <!-- Header Logo -->
            <div class="flex items-center justify-between pb-8 border-b border-slate-200 dark:border-slate-800/60">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-4 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-600 via-amber-500 to-amber-400 flex items-center justify-center font-heading font-black text-white dark:text-slate-950 text-lg shadow-[0_0_20px_rgba(245,158,11,0.3)] group-hover:scale-105 transition-transform duration-300">
                        AAI
                    </div>
                    <div>
                        <span class="block font-heading font-black text-sm tracking-widest text-slate-900 dark:text-transparent dark:bg-clip-text dark:bg-gradient-to-r dark:from-white dark:to-slate-400 group-hover:text-amber-600 dark:group-hover:from-amber-400 dark:group-hover:to-amber-200 transition-colors">ENTERPRISE</span>
                        <span class="block text-[10px] text-amber-600 dark:text-amber-500/80 font-bold uppercase tracking-widest mt-0.5">Dewan Pengurus Nasional</span>
                    </div>
                </a>
                
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode" class="p-2 rounded-full bg-slate-200/50 hover:bg-slate-200 dark:bg-slate-800/50 dark:hover:bg-slate-800 text-slate-600 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-400 transition-colors hidden sm:block">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
            </div>

            <!-- Role Badge -->
            <div class="mt-6 mb-4 p-3 bg-white/50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700/50 flex items-center justify-between text-[11px] text-slate-600 dark:text-slate-400 backdrop-blur-md shadow-inner">
                <span class="uppercase tracking-wider font-semibold">Otoritas Sesi</span>
                <span class="text-amber-600 dark:text-amber-400 font-black tracking-widest uppercase bg-amber-500/10 dark:bg-amber-400/10 px-2 py-1 rounded-md">{{ auth()->user()->roles->first()?->name ?? 'Pengurus' }}</span>
            </div>

            <!-- Guarded Navigation Links -->
            <nav class="mt-4 text-xs font-medium space-y-6 pb-4 overflow-y-auto custom-scrollbar h-[calc(100vh-22rem)] pr-2">
                <!-- Group 1: Main -->
                <div class="space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span class="tracking-wide">Dasbor Eksekutif KPI</span>
                    </a>
                </div>

                <!-- Group 2: Keanggotaan & Organisasi -->
                @if(auth()->user()->hasAnyRole(['super-admin', 'administrator', 'sekretariat-nasional', 'pengurus-wilayah', 'verifier-anggota']))
                <div class="space-y-1.5">
                    <div class="px-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Keanggotaan & Organisasi</div>
                    
                    @if(auth()->user()->hasAnyRole(['super-admin', 'administrator', 'sekretariat-nasional', 'pengurus-wilayah', 'verifier-anggota']))
                    <a href="{{ route('admin.members.verification-queue') }}" class="nav-item flex items-center justify-between px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.members.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.members.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="tracking-wide">Verifikasi Anggota & KTA</span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-red-500/90 text-white text-[10px] font-mono font-black shadow-sm">{{ \App\Domain\Membership\Models\Member::where('status', 'pending')->count() }}</span>
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAnyRole(['super-admin', 'administrator', 'pengurus-wilayah']))
                    <a href="{{ route('admin.organization.units.index') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.organization.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.organization.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="tracking-wide">Unit Organisasi</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasAnyRole(['super-admin', 'administrator']))
                    <a href="{{ route('admin.voting.elections.index') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.voting.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.voting.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        <span class="tracking-wide">E-Voting / Pemilu</span>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Group 3: Layanan & Kompetensi -->
                @if(auth()->user()->hasAnyRole(['super-admin', 'administrator']))
                <div class="space-y-1.5">
                    <div class="px-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Layanan & Kompetensi</div>
                    
                    <a href="{{ route('admin.events.index') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.events.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.events.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="tracking-wide">Manajemen Event</span>
                    </a>
                    <a href="{{ route('admin.lsp.batches.index') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.lsp.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.lsp.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        <span class="tracking-wide">Sertifikasi LSP</span>
                    </a>
                </div>
                @endif

                <!-- Group 4: Administrasi & Keuangan -->
                @if(auth()->user()->hasAnyRole(['super-admin', 'administrator', 'bendahara-nasional', 'sekretariat-nasional']))
                <div class="space-y-1.5">
                    <div class="px-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Administrasi & Keuangan</div>
                    
                    @if(auth()->user()->hasAnyRole(['super-admin', 'administrator', 'bendahara-nasional']))
                    <a href="{{ route('admin.finance.payments.index') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.finance.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.finance.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="tracking-wide">Keuangan & Invoice</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasAnyRole(['super-admin', 'administrator', 'sekretariat-nasional']))
                    <a href="{{ route('admin.correspondence.out.index') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.correspondence.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.correspondence.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="tracking-wide">E-Office Tata Naskah</span>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Group 5: Portal & Sistem -->
                @if(auth()->user()->hasAnyRole(['super-admin', 'administrator']))
                <div class="space-y-1.5">
                    <div class="px-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Portal & Sistem CMS</div>
                    
                    <a href="{{ route('admin.cms.articles.index') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.cms.articles.*') || request()->routeIs('admin.cms.news.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.cms.articles.*') || request()->routeIs('admin.cms.news.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
                        <span class="tracking-wide">CMS & Artikel Web</span>
                    </a>
                    <a href="{{ route('admin.cms.pages.index') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.cms.pages.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.cms.pages.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span class="tracking-wide">Halaman Dinamis</span>
                    </a>
                    <a href="{{ route('admin.cms.menus.index') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.cms.menus.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white dark:text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.cms.menus.*') ? 'text-white dark:text-slate-950' : 'text-amber-600 dark:text-amber-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <span class="tracking-wide">Pengaturan Menu</span>
                    </a>
                </div>
                @endif

                <!-- Group 6: Pindah Layanan -->
                <div class="space-y-1.5 pt-2">
                    <div class="pb-1 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest px-4 border-t border-slate-200 dark:border-slate-800/60 pt-4">Pindah Layanan</div>
                    <a href="{{ route('portal.dashboard') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                        <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="tracking-wide">Portal Member Mandiri</span>
                    </a>
                    <a href="{{ route('front.home') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                        <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="tracking-wide">Web Publik Publik</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Admin User Footer -->
        <div class="pt-6 mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3.5 bg-white/50 dark:bg-slate-900/50 hover:bg-red-50 dark:hover:bg-red-500/20 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 border border-slate-200 dark:border-slate-700/50 hover:border-red-300 dark:hover:border-red-500/50 rounded-xl text-xs font-bold tracking-wide transition-all duration-300 flex items-center justify-center gap-2 group backdrop-blur-sm">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Akhiri Sesi (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Executive Content Area -->
    <main class="flex-1 overflow-x-hidden p-6 sm:p-10 lg:p-12 relative z-10">
        <header class="flex flex-col xl:flex-row xl:items-end justify-between gap-6 mb-10 pb-8 border-b border-slate-200 dark:border-slate-800/60 relative">
            <div class="absolute bottom-0 left-0 w-1/3 h-[1px] bg-gradient-to-r from-amber-500 to-transparent"></div>
            
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-2.5 py-1 rounded-md bg-amber-500/10 text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-widest border border-amber-500/20">Data Enterprise</span>
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Sistem Pengolah Inti</span>
                </div>
                <h1 class="font-heading font-black text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight">Pusat Kendali <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-amber-600 dark:from-amber-400 dark:to-yellow-600">Eksekutif AAI</span></h1>
            </div>

            <!-- Server Integrity indicator -->
            <div class="flex items-center gap-4 text-xs bg-white/60 dark:bg-slate-900/60 backdrop-blur-md px-5 py-3 rounded-2xl border border-slate-200 dark:border-slate-700/50 text-slate-600 dark:text-slate-300 font-mono shadow-lg shadow-black/5 dark:shadow-black/20">
                <div class="flex items-center gap-2">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 shadow-[0_0_8px_#10b981]"></span>
                    </span>
                    <span class="font-bold">ENGINE: ACTIVE</span>
                </div>
                <div class="w-[1px] h-4 bg-slate-300 dark:bg-slate-700"></div>
                <div class="text-slate-500 font-medium">HORIZON: READY</div>
            </div>
        </header>

        <!-- Main Content Slot -->
        <div class="animate-fade-in-up">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
