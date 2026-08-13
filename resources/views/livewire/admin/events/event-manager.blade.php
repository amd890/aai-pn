<div>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-black text-slate-900 dark:text-white">Manajemen Event & Diklat</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola Seminar, Bimtek, dan Workshop AAI</p>
        </div>
        <button wire:click="create" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 font-bold text-sm rounded-xl transition shadow-glow-gold flex items-center gap-2">
            <span>+</span> Buat Event Baru
        </button>
    </div>

    @if($noticeMessage)
    <div class="mb-6 p-4 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm font-semibold flex items-center gap-3">
        <span>✓</span> {{ $noticeMessage }}
    </div>
    @endif

    <div class="bg-white dark:bg-slate-900/30 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden backdrop-blur-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/80 text-xs uppercase font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="py-4 px-6">Nama Event</th>
                        <th class="py-4 px-6">Jadwal Acara</th>
                        <th class="py-4 px-6">Format & Tipe</th>
                        <th class="py-4 px-6">Pendaftar</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($events as $event)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900 dark:text-white mb-1">{{ $event->title }}</div>
                            <div class="text-[10px] uppercase px-2 py-0.5 rounded inline-block border {{ $event->status === 'published' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/30' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600' }}">{{ $event->status }}</div>
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-600 dark:text-slate-300">
                            {{ \Carbon\Carbon::parse($event->event_start)->format('d M Y, H:i') }}<br>
                            s/d {{ \Carbon\Carbon::parse($event->event_end)->format('d M Y, H:i') }}
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-slate-900 dark:text-white">{{ ucfirst($event->format->value ?? $event->format) }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $event->is_free ? 'Gratis' : 'Berbayar (Rp '.number_format($event->price,0,',','.').')' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-mono font-black text-slate-900 dark:text-white">{{ $event->registrations_count }}</span>
                            <span class="text-xs text-slate-500">/ {{ $event->quota ?? '∞' }}</span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <button wire:click="edit({{ $event->id }})" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-amber-600 dark:text-amber-400 font-bold text-xs rounded-lg transition border border-slate-200 dark:border-slate-700">Edit</button>
                            <button wire:click="delete({{ $event->id }})" wire:confirm="Yakin hapus event ini?" class="px-3 py-1.5 bg-red-100 dark:bg-red-500/10 hover:bg-red-200 dark:hover:bg-red-500/20 text-red-600 dark:text-red-400 font-bold text-xs rounded-lg transition border border-red-200 dark:border-red-500/20">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-500 font-medium">Belum ada event atau diklat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
            {{ $events->links(data: ['scrollTo' => false]) }}
        </div>
        @endif
    </div>

    <!-- Event Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex justify-start">
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-slate-950/80 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
        <div class="relative w-full md:w-3/4 lg:w-3/4 bg-white dark:bg-slate-900 shadow-2xl flex flex-col h-full animate-slide-in-left overflow-hidden border-r border-slate-200 dark:border-slate-800">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 shrink-0">
                <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">{{ $activeEventId ? 'Edit Event' : 'Buat Event Baru' }}</h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto custom-scrollbar flex-1 min-h-0">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Judul Event / Diklat</label>
                    <input type="text" wire:model="title" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition outline-none">
                    @error('title') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Deskripsi Singkat</label>
                    <textarea wire:model="description" rows="2" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none"></textarea>
                    @error('description') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Format</label>
                        <select wire:model="format" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                            <option value="">Pilih Format</option>
                            @foreach($formatOptions as $opt)
                                <option value="{{ $opt->value }}">{{ ucfirst($opt->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Lokasi / URL Zoom</label>
                        <input type="text" wire:model="location" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Waktu Mulai Acara</label>
                        <input type="datetime-local" wire:model="event_start" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Waktu Selesai Acara</label>
                        <input type="datetime-local" wire:model="event_end" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Pendaftaran Buka</label>
                        <input type="datetime-local" wire:model="registration_start" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Pendaftaran Tutup</label>
                        <input type="datetime-local" wire:model="registration_end" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Kuota (Opsional)</label>
                        <input type="number" wire:model="quota" placeholder="Kosong = Unlimited" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Gratis?</label>
                        <select wire:model="is_free" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                            <option value="1">Ya, Gratis</option>
                            <option value="0">Tidak (Berbayar)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Status</label>
                        <select wire:model="status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 flex justify-start gap-3 shrink-0">
                <button wire:click="$set('showModal', false)" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition shadow-sm">Batal</button>
                <button wire:click="save" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 shadow-glow-gold transition">Simpan Event</button>
            </div>
        </div>
    </div>
    @endif
</div>
