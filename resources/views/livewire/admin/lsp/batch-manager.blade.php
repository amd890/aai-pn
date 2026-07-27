<div>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-black text-slate-900 dark:text-white">Administrasi Sertifikasi LSP & BNSP</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola Gelombang (Batch) Ujian dan Asesmen Kompetensi Arsiparis</p>
        </div>
        <button class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 font-bold text-sm rounded-xl transition shadow-glow-gold flex items-center gap-2">
            <span>+</span> Buka Batch Baru
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
                        <th class="py-4 px-6">Skema & Batch</th>
                        <th class="py-4 px-6">TUK & Asesor</th>
                        <th class="py-4 px-6">Jadwal Ujian</th>
                        <th class="py-4 px-6">Peserta</th>
                        <th class="py-4 px-6 text-right">Aksi Asesor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($batches as $batch)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900 dark:text-white mb-1">{{ $batch->scheme->name ?? 'Skema Umum' }}</div>
                            <div class="text-xs text-amber-600 dark:text-amber-400 font-mono font-bold">{{ $batch->batch_number }}</div>
                            <div class="text-xs text-slate-500 mt-1 uppercase">{{ $batch->status }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-slate-900 dark:text-white">{{ $batch->tuk->name ?? 'TUK Mandiri AAI' }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Asesor: {{ $batch->assessor->name ?? 'Belum Ditentukan' }}</div>
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-600 dark:text-slate-300">
                            {{ \Carbon\Carbon::parse($batch->scheduled_date)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($batch->end_date)->format('d M Y') }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-mono font-black text-slate-900 dark:text-white">{{ $batch->participants_count }}</span>
                            <span class="text-xs text-slate-500">/ {{ $batch->quota ?? '∞' }}</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button wire:click="manageParticipants({{ $batch->id }})" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-amber-600 dark:text-amber-400 font-bold text-xs rounded-lg transition border border-slate-200 dark:border-slate-700">
                                Asesmen Peserta
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-500 font-medium">Belum ada batch sertifikasi yang dibuka.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Asesmen -->
    @if($showParticipantModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-slate-950/80 backdrop-blur-sm" wire:click="$set('showParticipantModal', false)"></div>
        <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl w-full max-w-5xl max-h-[85vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 shrink-0">
                <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">Asesmen & Penerbitan Sertifikat Peserta</h3>
                <button wire:click="$set('showParticipantModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 min-h-0">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-900/80 text-xs uppercase font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-4 px-6">Nama Peserta</th>
                            <th class="py-4 px-6">Tanggal Asesmen</th>
                            <th class="py-4 px-6">Sertifikat BNSP</th>
                            <th class="py-4 px-6 text-right">Keputusan Asesor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($activeParticipants as $p)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 dark:text-white mb-1">{{ $p->member->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">ID Anggota: {{ $p->member_id }}</div>
                                <div class="text-[10px] font-bold uppercase mt-1 px-2 py-0.5 rounded inline-block bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">{{ $p->status->value ?? $p->status }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-600 dark:text-slate-300">
                                {{ $p->assessment_date ? \Carbon\Carbon::parse($p->assessment_date)->format('d M Y') : 'Belum Diasesmen' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($p->certificate)
                                    <div class="font-mono text-emerald-600 dark:text-emerald-400 font-bold text-xs">{{ $p->certificate->certificate_number }}</div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Tercetak Otomatis</div>
                                @else
                                    <span class="text-xs text-slate-500 italic">Belum Diterbitkan</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                @if(($p->status->value ?? $p->status) !== 'competent' && ($p->status->value ?? $p->status) !== 'not_competent')
                                <button wire:click="passParticipant({{ $p->id }})" wire:confirm="Sertifikat resmi BNSP akan dibuat. Lanjutkan?" class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-500/20 hover:bg-emerald-200 dark:hover:bg-emerald-500/30 text-emerald-700 dark:text-emerald-400 font-bold text-xs rounded border border-emerald-300 dark:border-emerald-500/30 transition">
                                    KOMPETEN
                                </button>
                                <button wire:click="failParticipant({{ $p->id }})" class="px-3 py-1.5 bg-red-100 dark:bg-red-500/20 hover:bg-red-200 dark:hover:bg-red-500/30 text-red-600 dark:text-red-400 font-bold text-xs rounded border border-red-300 dark:border-red-500/30 transition">
                                    B.K.
                                </button>
                                @elseif(($p->status->value ?? $p->status) === 'competent')
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs">LULUS</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400 font-bold text-xs">BELUM KOMPETEN</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-500 font-medium">Belum ada peserta terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
