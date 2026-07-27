<div class="pt-16 pb-24 max-w-md mx-auto px-4 sm:px-6">
    <div class="glass-card rounded-3xl p-8 sm:p-10 border border-slate-200 dark:border-slate-800 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-40 h-40 bg-amber-500/10 rounded-full blur-2xl"></div>

        <div class="text-center mb-8 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center font-heading font-black text-white dark:text-slate-950 text-xl mx-auto mb-4 shadow-glow-gold">
                AAI
            </div>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white">Portal Masuk</h1>
            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm mt-1">Akses area anggota & dasbor pengelola AAI</p>
        </div>

        @if($errorMessage)
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-semibold flex items-center gap-2.5">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $errorMessage }}</span>
        </div>
        @endif

        <form wire:submit="login" class="space-y-5 relative z-10">
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email Resmi</label>
                <input wire:model="email" type="email" id="email" required autofocus placeholder="nama@instansi.go.id"
                       class="w-full px-4 py-3 bg-white/90 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 transition shadow-inner">
                @error('email') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Kata Sandi</label>
                    <a href="#" class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:underline">Lupa sandi?</a>
                </div>
                <input wire:model="password" type="password" id="password" required placeholder="••••••••"
                       class="w-full px-4 py-3 bg-white/90 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 transition shadow-inner">
                @error('password') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <input wire:model="remember" type="checkbox" id="remember" class="w-4 h-4 rounded bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-0">
                <label for="remember" class="ml-2 text-xs font-medium text-slate-600 dark:text-slate-400">Ingat sesi saya di perangkat ini</label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 font-heading font-extrabold rounded-xl shadow-glow-gold hover:scale-102 transition duration-200 flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="login">Masuk ke Portal</span>
                <span wire:loading wire:target="login">Memverifikasi...</span>
                <svg wire:loading.remove wire:target="login" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-600 dark:text-slate-400">
            Belum tergabung di AAI Nasional? <br>
            <a href="{{ route('register') }}" class="text-amber-600 dark:text-amber-400 font-bold hover:underline mt-1 inline-block">Daftar Keanggotaan KTA &rarr;</a>
        </div>
    </div>
</div>
