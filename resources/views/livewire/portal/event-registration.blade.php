<div class="max-w-6xl mx-auto space-y-8">
    <div class="glass-card rounded-3xl p-8 border border-slate-800 shadow-2xl relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-950">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div>
            <h1 class="text-3xl font-heading font-black text-white mb-2">Event & Diklat AAI</h1>
            <p class="text-slate-400 max-w-2xl">Daftarkan diri Anda di seminar, workshop, dan bimbingan teknis (Bimtek) kearsipan nasional. Dapatkan E-Certificate bernilai jam pelajaran (JP).</p>
        </div>
    </div>

    @if($noticeMessage)
    <div class="p-4 rounded-xl {{ str_contains($noticeMessage, 'berhasil') ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-red-500/10 border-red-500/30 text-red-400' }} font-bold flex items-center gap-3 border">
        {{ $noticeMessage }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upcoming Events -->
        <div class="space-y-4">
            <h2 class="text-lg font-heading font-black text-amber-400 mb-4 border-b border-slate-800 pb-2">Event Mendatang</h2>
            
            @forelse($upcomingEvents as $event)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-amber-500/50 transition relative overflow-hidden">
                @if($event->is_free)
                    <div class="absolute top-4 right-4 px-2 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded text-[10px] font-bold uppercase">GRATIS</div>
                @else
                    <div class="absolute top-4 right-4 px-2 py-1 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded text-[10px] font-bold uppercase">BERBAYAR</div>
                @endif

                <h3 class="font-bold text-white text-lg pr-16 mb-2">{{ $event->title }}</h3>
                <p class="text-sm text-slate-400 mb-4 line-clamp-2">{{ $event->description }}</p>
                
                <div class="space-y-2 mb-6 text-sm text-slate-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ \Carbon\Carbon::parse($event->event_start)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $event->location ?? ucfirst($event->format->value ?? $event->format) }}</span>
                    </div>
                </div>

                @if($event->isRegistrationOpen())
                <button wire:click="registerForEvent({{ $event->id }})" class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm rounded-xl transition shadow border border-slate-700">
                    Daftar Event Ini
                </button>
                @else
                <button disabled class="w-full py-2.5 bg-slate-900 text-slate-500 font-bold text-sm rounded-xl cursor-not-allowed border border-slate-800">
                    Pendaftaran Tutup / Penuh
                </button>
                @endif
            </div>
            @empty
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center">
                <p class="text-slate-500">Belum ada event mendatang.</p>
            </div>
            @endforelse
        </div>

        <!-- My Tickets -->
        <div class="space-y-4">
            <h2 class="text-lg font-heading font-black text-blue-400 mb-4 border-b border-slate-800 pb-2">Tiket & Pendaftaran Saya</h2>
            
            @forelse($myTickets as $ticket)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 relative">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-white text-md mb-1">{{ $ticket->event->title }}</h3>
                        <span class="px-2 py-1 {{ $ticket->status === 'confirmed' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-slate-800 text-slate-400 border-slate-700' }} border rounded text-[10px] font-bold uppercase">{{ $ticket->status }}</span>
                    </div>
                </div>

                @if($ticket->status === 'confirmed')
                <div class="mt-4 p-4 bg-slate-950 border border-slate-800 rounded-xl relative overflow-hidden flex items-center justify-between">
                    <div>
                        <div class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">E-Ticket QR</div>
                        <div class="text-sm font-mono font-bold text-amber-400">TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-white rounded border border-slate-200 flex items-center justify-center p-1">
                        <!-- Mock QR Code -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=TKT-{{ $ticket->id }}" alt="QR" class="w-full h-full opacity-80 mix-blend-multiply">
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center">
                <p class="text-slate-500">Anda belum mendaftar event apapun.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
