<div class="max-w-6xl mx-auto space-y-8">
    <div class="glass-card rounded-3xl p-8 border border-slate-800 shadow-2xl relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-950 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div>
            <h1 class="text-3xl font-heading font-black text-white mb-2">Sertifikasi Profesi LSP AAI</h1>
            <p class="text-slate-400 max-w-2xl">Tingkatkan karir arsiparis Anda dengan sertifikasi BNSP kompetensi. Daftar batch asesmen dan dapatkan E-Certificate resmi bernomor QR.</p>
        </div>
        <div class="shrink-0 flex items-center justify-center w-24 h-24 rounded-full border-4 border-slate-800 bg-slate-900 shadow-inner">
            <span class="font-heading font-black text-2xl text-emerald-400">BNSP</span>
        </div>
    </div>

    @if($noticeMessage)
    <div class="p-4 rounded-xl {{ str_contains($noticeMessage, 'Gagal') ? 'bg-red-500/10 border-red-500/30 text-red-400' : 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' }} font-bold flex items-center gap-3">
        {{ $noticeMessage }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Open Batches -->
        <div class="space-y-4">
            <h2 class="text-lg font-heading font-black text-amber-400 mb-4 border-b border-slate-800 pb-2">Gelombang Pendaftaran Aktif</h2>
            
            @forelse($openBatches as $batch)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-amber-500/50 transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-white text-lg">{{ $batch->scheme->name ?? 'Skema Umum' }}</h3>
                        <div class="text-xs text-amber-400 font-mono font-bold mt-1">{{ $batch->batch_number }}</div>
                    </div>
                    <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded text-[10px] font-bold uppercase">Open</span>
                </div>
                
                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 text-sm text-slate-400">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ \Carbon\Carbon::parse($batch->scheduled_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($batch->end_date)->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-400">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $batch->tuk->name ?? 'TUK Online/Mandiri' }}</span>
                    </div>
                </div>

                <button wire:click="registerForBatch({{ $batch->id }})" wire:confirm="Anda akan didaftarkan ke skema asesmen ini. Pastikan Anda telah mempersiapkan dokumen APL-01 & APL-02. Lanjutkan?" class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm rounded-xl transition shadow-lg border border-slate-700">
                    Daftar Sekarang
                </button>
            </div>
            @empty
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center">
                <p class="text-slate-500">Belum ada gelombang sertifikasi yang dibuka.</p>
            </div>
            @endforelse
        </div>

        <!-- My Registrations & Certificates -->
        <div class="space-y-4">
            <h2 class="text-lg font-heading font-black text-emerald-400 mb-4 border-b border-slate-800 pb-2">Riwayat & Sertifikat Saya</h2>
            
            @forelse($myRegistrations as $reg)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-white text-md">{{ $reg->batch->scheme->name ?? 'Skema Umum' }}</h3>
                        <p class="text-xs text-slate-400 mt-1">{{ $reg->batch->batch_number ?? '' }}</p>
                    </div>
                    @if(($reg->status->value ?? $reg->status) === 'competent')
                        <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded text-[10px] font-bold uppercase">KOMPETEN</span>
                    @elseif(($reg->status->value ?? $reg->status) === 'not_competent')
                        <span class="px-2 py-1 bg-red-500/20 text-red-400 border border-red-500/30 rounded text-[10px] font-bold uppercase">B.K.</span>
                    @else
                        <span class="px-2 py-1 bg-slate-800 text-slate-400 border border-slate-700 rounded text-[10px] font-bold uppercase">PROSES</span>
                    @endif
                </div>

                @if($reg->certificate)
                <div class="mt-4 p-4 bg-slate-950 border border-slate-800 rounded-xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Nomor Sertifikat (BNSP/LSP)</div>
                    <div class="text-lg font-mono font-black text-amber-400">{{ $reg->certificate->certificate_number }}</div>
                    <div class="text-xs text-slate-500 mt-2">Diterbitkan: {{ \Carbon\Carbon::parse($reg->certificate->issued_at)->format('d M Y') }}</div>
                </div>
                @else
                <div class="mt-4 text-xs text-slate-500 p-3 bg-slate-950 border border-slate-800 rounded-lg italic">
                    Menunggu proses asesmen dan penerbitan sertifikat.
                </div>
                @endif
            </div>
            @empty
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center">
                <p class="text-slate-500">Anda belum mendaftar uji kompetensi apapun.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
