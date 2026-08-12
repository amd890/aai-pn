<div>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-black text-slate-900 dark:text-white">Manajemen E-Voting (KPU AAI)</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola Pemilihan Ketua Nasional/Wilayah dan Real-Time Tally Center</p>
        </div>
        <button wire:click="createElection" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white dark:text-slate-950 font-bold text-sm rounded-xl transition shadow-glow-emerald flex items-center gap-2">
            <span>+</span> Buat Pemilihan Baru
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
                        <th class="py-4 px-6">Nama Pemilihan</th>
                        <th class="py-4 px-6">Tingkat</th>
                        <th class="py-4 px-6">Periode</th>
                        <th class="py-4 px-6">Suara Masuk</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($elections as $election)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900 dark:text-white mb-1">{{ $election->title }}</div>
                            <div class="text-xs font-semibold px-2 py-0.5 rounded {{ ($election->status->value ?? $election->status) === 'open' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700' }} inline-block uppercase">
                                {{ $election->status->value ?? $election->status }}
                            </div>
                        </td>
                        <td class="py-4 px-6 uppercase text-xs font-bold text-amber-600 dark:text-amber-400">
                            {{ $election->level->value ?? $election->level }}
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-500 dark:text-slate-400">
                            <div>Start: <span class="text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($election->start_at)->format('d M Y H:i') }}</span></div>
                            <div>End: <span class="text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($election->end_at)->format('d M Y H:i') }}</span></div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-mono font-black text-lg text-slate-900 dark:text-white">{{ $election->votes_count }}</span>
                            <span class="text-xs text-slate-500">Votes</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button wire:click="manageCandidates({{ $election->id }})" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-bold text-xs rounded-lg transition border border-slate-200 dark:border-slate-700">
                                Kandidat & Tally
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-500 font-medium">Belum ada pemilihan yang dikelola.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Election Modal -->
    @if($showElectionModal)
    <div class="fixed inset-0 z-50 flex justify-end">
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-slate-950/80 backdrop-blur-sm" wire:click="$set('showElectionModal', false)"></div>
        <div class="relative w-full md:w-3/4 lg:w-3/4 bg-white dark:bg-slate-900 shadow-2xl flex flex-col h-full animate-slide-in-right overflow-hidden border-l border-slate-200 dark:border-slate-800">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 shrink-0">
                <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">Buat Pemilihan Baru</h3>
                <button wire:click="$set('showElectionModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 min-h-0">
                <form wire:submit="saveElection" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Judul Pemilihan</label>
                        <input wire:model="title" type="text" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Tingkat</label>
                            <select wire:model="level" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 transition">
                                <option value="nasional">Nasional (Munas)</option>
                                <option value="wilayah">Wilayah (Muswil)</option>
                                <option value="cabang">Cabang (Muscab)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Status</label>
                            <select wire:model="status" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 transition">
                                <option value="draft">Draft / Persiapan</option>
                                <option value="open">Open (Berlangsung)</option>
                                <option value="closed">Closed (Selesai)</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Waktu Mulai</label>
                            <input wire:model="start_at" type="datetime-local" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-700 dark:text-slate-300 focus:outline-none focus:border-amber-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Waktu Selesai</label>
                            <input wire:model="end_at" type="datetime-local" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-700 dark:text-slate-300 focus:outline-none focus:border-amber-500 transition">
                        </div>
                    </div>
                </form>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3 shrink-0">
                <button wire:click="$set('showElectionModal', false)" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition shadow-sm">Batal</button>
                <button wire:click="saveElection" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 shadow-glow-gold transition">Buat Pemilu</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Candidates Modal -->
    @if($showCandidateModal)
    <div class="fixed inset-0 z-50 flex justify-end">
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-slate-950/80 backdrop-blur-sm" wire:click="$set('showCandidateModal', false)"></div>
        <div class="relative w-full md:w-3/4 lg:w-3/4 bg-white dark:bg-slate-900 shadow-2xl flex flex-col h-full animate-slide-in-right overflow-hidden border-l border-slate-200 dark:border-slate-800">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 shrink-0">
                <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">Real-Time Tally & Kandidat</h3>
                <button wire:click="$set('showCandidateModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8 overflow-y-auto custom-scrollbar flex-1 min-h-0">
                <!-- Tally Center / List Kandidat -->
                <div class="lg:col-span-2 space-y-6">
                    <h4 class="font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">Perolehan Suara (Live)</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($activeCandidates as $candidate)
                        <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-5 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-3">
                                <span class="font-mono text-3xl font-black text-amber-500/10 dark:text-amber-500/20 group-hover:text-amber-500/30 dark:group-hover:text-amber-500/40 transition">#{{ $candidate->candidate_number }}</span>
                            </div>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center font-heading font-bold text-xl text-slate-600 dark:text-slate-400">
                                    {{ substr($candidate->member->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $candidate->member->name ?? 'Anggota ID: ' . $candidate->member_id }}</div>
                                    <div class="text-xs text-slate-500">Kandidat Ketua</div>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800/80">
                                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Suara Masuk</div>
                                <div class="text-3xl font-mono font-black text-emerald-600 dark:text-emerald-400">{{ $candidate->vote_count }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 text-center text-slate-500 py-8">Belum ada kandidat terdaftar.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Form Tambah Kandidat -->
                <div class="bg-slate-50 dark:bg-slate-950/50 rounded-xl p-5 border border-slate-200 dark:border-slate-800">
                    <h4 class="font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2 mb-4">Daftarkan Kandidat</h4>
                    <form wire:submit="saveCandidate" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">ID Anggota AAI</label>
                            <input wire:model="candidateMemberId" type="number" required placeholder="Contoh: 1" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 text-sm">
                            <p class="text-[10px] text-slate-500 mt-1">ID sistem dari tabel members</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Nomor Urut</label>
                            <input wire:model="candidateNumber" type="number" required placeholder="Contoh: 1" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Visi Misi Singkat</label>
                            <textarea wire:model="visionMission" required rows="3" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm rounded-lg border border-slate-700 hover:border-slate-500 transition">
                            Tambah Kandidat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
