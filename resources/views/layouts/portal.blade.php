<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Member Self-Service — Asosiasi Arsiparis Indonesia')</title>
    
    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        .font-heading { font-family: 'Outfit', sans-serif; }
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .glass-sidebar {
            background: rgba(15, 23, 42, 0.6);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        .nav-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-item:hover { transform: translateX(4px); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans antialiased selection:bg-amber-500 selection:text-white min-h-screen flex flex-col sm:flex-row relative overflow-x-hidden transition-colors duration-300">
    
    <!-- Premium Ambient Gradients -->
    <div class="fixed inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-0 dark:opacity-[0.03] pointer-events-none z-0"></div>
    <div class="fixed top-[-10%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-aai-blue/5 dark:from-aai-blue/20 via-slate-50/0 dark:via-slate-950/0 to-transparent blur-[140px] pointer-events-none z-0 transition-colors duration-500"></div>
    <div class="fixed bottom-[-20%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-aai-orange/5 dark:from-aai-orange/10 via-slate-50/0 dark:via-slate-950/0 to-transparent blur-[130px] pointer-events-none z-0 transition-colors duration-500"></div>

    <!-- Left Sidebar -->
    <aside class="w-full sm:w-72 glass-sidebar shrink-0 flex flex-col justify-between p-6 relative z-20 shadow-[4px_0_24px_rgba(0,0,0,0.4)]">
        <div>
            <!-- Branding Logo -->
            <div class="flex items-center justify-between pb-8 border-b border-slate-200 dark:border-slate-800/60">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center space-x-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center font-heading font-black text-white dark:text-slate-950 text-lg shadow-[0_0_20px_rgba(245,158,11,0.25)] group-hover:scale-105 transition-transform duration-300">
                        AAI
                    </div>
                    <div>
                        <span class="block font-heading font-black text-sm tracking-widest text-slate-900 dark:text-transparent dark:bg-clip-text dark:bg-gradient-to-r dark:from-white dark:to-slate-400 group-hover:text-amber-600 dark:group-hover:from-amber-400 dark:group-hover:to-amber-200 transition-colors">MEMBER PORTAL</span>
                        <span class="block text-[10px] text-amber-600 dark:text-amber-500/80 font-bold uppercase tracking-widest mt-0.5">Self-Service System</span>
                    </div>
                </a>
                
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode" class="p-2 rounded-full bg-slate-200/50 hover:bg-slate-200 dark:bg-slate-800/50 dark:hover:bg-slate-800 text-slate-600 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-400 transition-colors hidden sm:block">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-8 space-y-1.5 text-xs font-medium">
                <a href="{{ route('portal.dashboard') }}" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl bg-gradient-to-r from-amber-500/15 to-transparent border-l-4 border-amber-500 text-slate-900 dark:text-white font-bold tracking-wide shadow-sm transition-all relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-transparent opacity-0 hover:opacity-100 transition-opacity"></div>
                    <svg class="w-5 h-5 text-amber-500 dark:text-amber-400 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="relative z-10">Dasbor & KTA Digital</span>
                </a>

                @if(auth()->user()->hasAnyRole(['super-admin', 'administrator', 'sekretariat-nasional', 'bendahara-nasional', 'pengurus-wilayah', 'verifier-anggota', 'lsp-admin']))
                <div class="pt-6 pb-2 text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest px-4 border-t border-slate-200 dark:border-slate-800/60 mt-4">Hak Akses Petugas</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center justify-between px-4 py-3 rounded-xl text-amber-600 dark:text-amber-400/70 hover:text-amber-700 dark:hover:text-amber-400 hover:bg-amber-500/10 font-bold transition-all border border-transparent hover:border-amber-500/20">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="tracking-wide">Ke Admin Eksekutif</span>
                    </div>
                    <span class="text-[10px]">&rarr;</span>
                </a>
                @endif

                <div class="pt-6 pb-2 text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest px-4 {{ !auth()->user()->hasAnyRole(['super-admin', 'administrator', 'sekretariat-nasional', 'bendahara-nasional', 'pengurus-wilayah', 'verifier-anggota', 'lsp-admin']) ? 'border-t border-slate-200 dark:border-slate-800/60 mt-4' : '' }}">Situs Publik</div>
                
                <a href="{{ route('front.home') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    <span class="tracking-wide">Kembali ke Beranda</span>
                </a>
            </nav>
        </div>

        <!-- User Footer & Logout -->
        <div class="pt-6 mt-auto">
            <div class="flex items-center space-x-3 mb-5 p-3 rounded-xl bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700/30 backdrop-blur-md">
                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 border-2 border-amber-500 flex items-center justify-center text-xs font-black text-amber-600 dark:text-amber-400 shadow-inner shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <span class="block font-heading font-black text-xs text-slate-900 dark:text-white truncate tracking-wide">{{ auth()->user()->name ?? 'Anggota AAI' }}</span>
                    <span class="block text-[10px] font-medium text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ auth()->user()->email ?? 'member@aai.id' }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3.5 bg-white/40 dark:bg-slate-900/40 hover:bg-red-50 dark:hover:bg-red-500/20 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 border border-slate-200 dark:border-slate-700/50 hover:border-red-300 dark:hover:border-red-500/40 rounded-xl text-xs font-bold tracking-wide transition-all flex items-center justify-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar (Log Out)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-x-hidden p-6 sm:p-10 lg:p-12 relative z-10">
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 pb-8 border-b border-slate-200 dark:border-slate-800/60 relative">
            <div class="absolute bottom-0 left-0 w-1/4 h-[1px] bg-gradient-to-r from-amber-500 to-transparent"></div>
            
            <div>
                <h1 class="font-heading font-black text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight">Portal Arsiparis <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-amber-600 dark:from-amber-400 dark:to-yellow-600">Mandiri</span></h1>
                <p class="text-slate-600 dark:text-slate-400 font-medium text-sm mt-2 max-w-lg">Sistem satu pintu untuk manajemen profil keanggotaan, iuran wajib, dan perpanjangan Sertifikat & KTA Digital.</p>
            </div>

            <!-- Live time / Server Status -->
            <div class="flex items-center gap-3 text-xs font-mono font-medium text-slate-600 dark:text-slate-300 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md px-5 py-3 rounded-2xl border border-slate-200 dark:border-slate-700/50 shadow-lg shadow-black/5 dark:shadow-black/10 shrink-0">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 shadow-[0_0_8px_#10b981]"></span>
                </span>
                <span>STATUS: SECURE &bull; {{ date('d M Y') }}</span>
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
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
