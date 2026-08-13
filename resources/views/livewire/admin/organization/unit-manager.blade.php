<div>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-black text-slate-900 dark:text-white">Struktur Organisasi (PW & PC)</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola Pengurus Wilayah (Provinsi) dan Cabang (Kab/Kota)</p>
        </div>
        <button wire:click="create" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 font-bold text-sm rounded-xl transition shadow-glow-gold flex items-center gap-2">
            <span>+</span> Tambah Unit
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
                        <th class="py-4 px-6">Nama Unit</th>
                        <th class="py-4 px-6">Kode & Tipe</th>
                        <th class="py-4 px-6">Induk Organisasi</th>
                        <th class="py-4 px-6">Kontak</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($units as $unit)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900 dark:text-white mb-1">{{ $unit->name }}</div>
                            <div class="text-[10px] uppercase px-2 py-0.5 rounded inline-block border {{ $unit->status === 'active' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/30' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600' }}">{{ $unit->status }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-amber-600 dark:text-amber-400 font-mono font-bold">{{ $unit->code }}</div>
                            <div class="text-xs text-slate-500 mt-0.5 uppercase">{{ ucfirst($unit->type->value ?? $unit->type) }}</div>
                        </td>
                        <td class="py-4 px-6">
                            @if($unit->parent)
                                <span class="text-slate-900 dark:text-white text-xs">{{ $unit->parent->name }}</span>
                            @else
                                <span class="text-slate-500 text-xs italic">- Root -</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-500 dark:text-slate-400">
                            <div>{{ $unit->email ?? '-' }}</div>
                            <div>{{ $unit->phone ?? '-' }}</div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button wire:click="edit({{ $unit->id }})" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-amber-600 dark:text-amber-400 font-bold text-xs rounded-lg transition border border-slate-200 dark:border-slate-700">Edit</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-500 font-medium">Belum ada unit organisasi terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($units->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
            {{ $units->links(data: ['scrollTo' => false]) }}
        </div>
        @endif
    </div>

    <!-- Unit Form Modal -->
    <div x-data="{ show: @entangle('showModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 flex justify-end">
        <div x-show="show" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/40 dark:bg-slate-950/80 backdrop-blur-sm" @click="show = false"></div>
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="relative w-full md:w-3/4 lg:w-3/4 bg-white dark:bg-slate-900 shadow-2xl flex flex-col h-full overflow-hidden border-l border-slate-200 dark:border-slate-800">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 shrink-0">
                <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">{{ $activeUnitId ? 'Edit Unit Organisasi' : 'Tambah Unit Baru' }}</h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-5 overflow-y-auto custom-scrollbar flex-1 min-h-0">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Nama Unit / Cabang</label>
                        <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Kode (Ex: PW-JABAR)</label>
                        <input type="text" wire:model="code" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Tingkat/Tipe</label>
                        <select wire:model="type" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                            <option value="">Pilih Tipe</option>
                            @foreach($types as $opt)
                                <option value="{{ $opt->value }}">{{ ucfirst($opt->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Induk Organisasi (Opsional)</label>
                        <select wire:model="parent_id" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                            <option value="">- Tanpa Induk (Pusat) -</option>
                            @foreach($parentOptions as $opt)
                                <option value="{{ $opt->id }}">{{ $opt->name }} ({{ $opt->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Email Sekretariat</label>
                        <input type="email" wire:model="email" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Telepon</label>
                        <input type="text" wire:model="phone" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:border-amber-500 outline-none">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 flex justify-start gap-3 shrink-0">
                <button wire:click="$set('showModal', false)" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition shadow-sm">Batal</button>
                <button wire:click="save" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 shadow-glow-gold transition">Simpan Data</button>
            </div>
        </div>
    </div>
</div>
