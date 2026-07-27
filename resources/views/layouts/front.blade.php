@php
    $navMenus = \App\Domain\CMS\Models\Menu::where('is_active', true)->orderBy('order')->get()->groupBy('location');
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Asosiasi Arsiparis Indonesia — Portal Kearsipan')</title>
    <meta name="description" content="@yield('meta_description', 'Portal Resmi Pengurus Nasional Asosiasi Arsiparis Indonesia (PN-AAI).')">
    <meta property="og:title" content="@yield('title', 'Asosiasi Arsiparis Indonesia')">
    
    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border: 1px solid rgba(0, 0, 0, 0.05); }
        .dark .glass-panel { background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); border: 1px solid rgba(245, 158, 11, 0.2); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .dark .glass-card { background: rgba(15, 23, 42, 0.8); box-shadow: none; }
        .glass-card:hover { transform: translateY(-4px); border-color: rgba(245, 158, 11, 0.6); box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.15); }
        .dark .glass-card:hover { box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.25); }
    </style>
</head>
<body class="font-sans bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 antialiased min-h-screen flex flex-col selection:bg-amber-500 selection:text-white relative overflow-x-hidden transition-colors duration-300">
    
    <!-- Premium Ambient Gradients -->
    <div class="fixed top-[-10%] left-[-10%] w-[70vw] h-[70vw] rounded-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-aai-blue/10 dark:from-aai-blue/20 via-slate-50/0 dark:via-slate-950/0 to-transparent blur-[120px] pointer-events-none z-0 transition-colors duration-500"></div>
    <div class="fixed bottom-[-20%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-aai-orange/10 dark:from-aai-orange/10 via-slate-50/0 dark:via-slate-950/0 to-transparent blur-[140px] pointer-events-none z-0 transition-colors duration-500"></div>

    <!-- Header & Navigation -->
    <header class="sticky top-0 z-50 glass-panel border-b border-slate-200 dark:border-slate-800/80 shadow-lg shadow-black/5 dark:shadow-black/20" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand -->
                <a href="{{ route('front.home') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center font-heading font-black text-slate-950 text-lg shadow-glow-gold group-hover:scale-105 transition-transform duration-300">
                        AAI
                    </div>
                    <div>
                        <span class="block font-heading font-bold text-lg tracking-wide text-slate-900 dark:text-white group-hover:text-amber-500 dark:group-hover:text-amber-400 transition-colors">Asosiasi Arsiparis</span>
                        <span class="block text-xs font-medium text-amber-600 dark:text-amber-500 uppercase tracking-widest">Indonesia</span>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center space-x-6">
                    @foreach($navMenus['header'] ?? [] as $menu)
                        <a href="{{ url($menu->url) }}" target="{{ $menu->target }}" class="font-medium text-sm text-aai-blue hover:text-aai-orange dark:text-slate-300 dark:hover:text-amber-400 transition-colors">{{ $menu->label }}</a>
                    @endforeach
                </nav>

                <!-- Actions -->
                <div class="hidden lg:flex items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode" class="p-2 rounded-full bg-slate-200/50 hover:bg-slate-200 dark:bg-slate-800/50 dark:hover:bg-slate-800 text-aai-blue hover:text-aai-orange dark:text-slate-400 dark:hover:text-amber-400 transition-colors">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="darkMode" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    
                    <a href="{{ route('front.membership.register') }}" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 shadow-lg shadow-amber-500/30 transition-all duration-200 transform hover:-translate-y-0.5">
                        Menjadi Anggota
                    </a>
                </div>

                <!-- Mobile Menu & Theme Buttons -->
                <div class="lg:hidden flex items-center space-x-2">
                    <button @click="darkMode = !darkMode" class="p-2 text-aai-blue hover:text-aai-orange dark:text-slate-400 dark:hover:text-amber-400 focus:outline-none">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="darkMode" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <button @click="mobileMenu = !mobileMenu" class="p-2 text-aai-blue hover:text-aai-orange dark:text-slate-400 dark:hover:text-amber-400 focus:outline-none">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Nav Drawer -->
        <div x-show="mobileMenu" x-transition class="lg:hidden border-t border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl px-4 pt-4 pb-6 space-y-2" style="display: none;">
            @foreach($navMenus['header'] ?? [] as $menu)
                <a href="{{ url($menu->url) }}" target="{{ $menu->target }}" class="block px-3 py-2 text-sm rounded-lg font-medium text-aai-blue hover:text-aai-orange hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">{{ $menu->label }}</a>
            @endforeach
            <div class="pt-4 mt-2 border-t border-slate-200 dark:border-slate-800 flex flex-col gap-2">
                <a href="{{ route('front.membership.register') }}" class="w-full py-2.5 text-center rounded-lg bg-amber-500 text-white dark:text-slate-950 font-bold">Menjadi Anggota</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-100/80 dark:bg-slate-950/80 backdrop-blur-md border-t border-slate-200 dark:border-slate-800/80 pt-16 pb-8 mt-24 relative overflow-hidden z-10">
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[80vw] h-[300px] bg-gradient-to-t from-aai-blue/10 dark:from-aai-blue/30 to-transparent blur-[100px] pointer-events-none transition-colors duration-500"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8 lg:gap-12 pb-12 border-b border-slate-200 dark:border-slate-800/60">
                <!-- Branding -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center font-heading font-black text-white dark:text-slate-950 shadow-glow-gold">AAI</div>
                        <span class="font-heading font-bold text-lg text-slate-900 dark:text-white">Bersama AAI</span>
                    </div>
                    <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        Pengurus Nasional Asosiasi Arsiparis Indonesia
                    </p>
                </div>

                <!-- Media & Publikasi -->
                <div>
                    <h3 class="font-heading font-semibold text-slate-900 dark:text-white mb-4">Media & Publikasi</h3>
                    <ul class="space-y-2.5 text-sm text-slate-600 dark:text-slate-400">
                        @foreach($navMenus['footer_media'] ?? [] as $menu)
                            <li><a href="{{ url($menu->url) }}" target="{{ $menu->target }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">{{ $menu->label }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Layanan Publik -->
                <div>
                    <h3 class="font-heading font-semibold text-slate-900 dark:text-white mb-4">Layanan Publik</h3>
                    <ul class="space-y-2.5 text-sm text-slate-600 dark:text-slate-400">
                        @foreach($navMenus['footer_services'] ?? [] as $menu)
                            <li><a href="{{ url($menu->url) }}" target="{{ $menu->target }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">{{ $menu->label }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Tautan Eksternal -->
                <div>
                    <h3 class="font-heading font-semibold text-slate-900 dark:text-white mb-4">Tautan Eksternal</h3>
                    <ul class="space-y-2.5 text-sm text-slate-600 dark:text-slate-400">
                        @foreach($navMenus['footer_external'] ?? [] as $menu)
                            <li><a href="{{ url($menu->url) }}" target="{{ $menu->target }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">{{ $menu->label }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="font-heading font-semibold text-slate-900 dark:text-white mb-4">Subscribe to our Newsletter</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Subscribe to our mailing list to receive daily updates direct to your inbox!</p>
                    <form class="flex" onsubmit="event.preventDefault();">
                        <input type="email" placeholder="Enter your email" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-l-xl px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 shadow-inner">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white dark:text-slate-950 font-bold px-4 py-2 rounded-r-xl transition-colors shadow-glow-gold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Copyright & Legal -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <div>&copy; {{ date('Y') }} Pengurus Nasional Asosiasi Arsiparis Indonesia. All rights reserved.</div>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('front.privacy-policy') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Kebijakan Privasi</a>
                    <a href="{{ route('front.terms') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Syarat & Ketentuan</a>
                    <a href="{{ route('front.cookies') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Keamanan & Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
