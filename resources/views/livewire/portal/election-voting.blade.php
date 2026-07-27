<div class="max-w-5xl mx-auto space-y-8" wire:poll.10s>
    <div class="glass-card rounded-3xl p-8 border border-slate-800 shadow-2xl relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-950">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <h1 class="text-3xl font-heading font-black text-white mb-2">Bilik Suara Digital KPU AAI</h1>
        <p class="text-slate-400">Pemungutan suara rahasia berbasis hash kriptografi. Suara Anda dijamin anonim dan tidak dapat dimanipulasi.</p>

        @if($noticeMessage)
        <div class="mt-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ $noticeMessage }}
        </div>
        @endif

        @if($errorMessage)
        <div class="mt-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 font-bold flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ $errorMessage }}
        </div>
        @endif
    </div>

    @forelse($elections as $election)
    <div class="glass-card rounded-2xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="bg-slate-800/40 p-6 border-b border-slate-800">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-heading font-black text-amber-400">{{ $election->title }}</h2>
                    <p class="text-slate-400 text-sm mt-1">{{ $election->description ?? 'Pemilihan resmi pengurus AAI.' }}</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-full text-xs font-bold uppercase animate-pulse">Live Voting</span>
                    <p class="text-xs text-slate-500 mt-2 font-mono">Ditutup: {{ \Carbon\Carbon::parse($election->end_at)->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
        
        <div class="p-6 bg-slate-900/60">
            <h3 class="text-sm font-bold text-slate-300 uppercase tracking-widest mb-6 text-center">Daftar Kandidat</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($election->candidates as $candidate)
                <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6 text-center relative group hover:border-amber-500/50 transition duration-300">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-slate-800 text-slate-300 border border-slate-700 w-8 h-8 rounded-full flex items-center justify-center font-black shadow-lg">
                        {{ $candidate->candidate_number }}
                    </div>
                    
                    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-slate-700 to-slate-800 mt-4 mb-4 border-2 border-slate-700 flex items-center justify-center text-3xl font-black text-slate-500">
                        {{ substr($candidate->member->name ?? '?', 0, 1) }}
                    </div>
                    
                    <h4 class="font-bold text-white text-lg mb-1">{{ $candidate->member->name ?? 'Anonim' }}</h4>
                    <p class="text-xs text-slate-400 mb-6 px-4 line-clamp-3">{{ $candidate->vision_mission }}</p>
                    
                    <button wire:click="castVote({{ $election->id }}, {{ $candidate->id }})" wire:confirm="Satu anggota hanya memiliki SATU HAK SUARA secara permanen pada pemilihan ini. Yakin memilih Kandidat Nomor {{ $candidate->candidate_number }} ({{ $candidate->member->name ?? '' }})?" class="w-full py-3 bg-slate-800 hover:bg-gradient-to-r hover:from-amber-500 hover:to-amber-600 text-white hover:text-slate-950 font-black text-sm rounded-xl transition shadow-lg border border-slate-700 hover:border-transparent">
                        COBLOS NOMOR {{ $candidate->candidate_number }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-20 glass-card rounded-3xl border border-slate-800">
        <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        <h3 class="text-xl font-bold text-slate-300">Tidak Ada Pemilihan Aktif</h3>
        <p class="text-slate-500 mt-2">Saat ini tidak ada bilik pemungutan suara elektronik yang sedang dibuka untuk Anda.</p>
    </div>
    @endforelse
</div>
