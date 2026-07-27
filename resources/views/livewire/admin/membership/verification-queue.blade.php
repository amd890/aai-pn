<div class="space-y-6">

    @if($noticeMessage)
    <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/40 text-emerald-400 text-sm font-bold flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $noticeMessage }}</span>
        </div>
        <button wire:click="$set('noticeMessage', '')" class="text-slate-400 hover:text-white px-2">&times;</button>
    </div>
    @endif

    <div class="bg-white/80 dark:bg-slate-900/80 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-xl backdrop-blur-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-slate-900 dark:text-white">Antrean Verifikasi & Otorisasi KTA</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm mt-1">Otentikasi kredensial pendaftar baru dan terbitkan Nomor Anggota AAI Resmi</p>
            </div>

            <!-- Filter & Search -->
            <div class="flex flex-col sm:flex-row gap-3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / Nomor KTA..."
                       class="px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500">
                
                <select wire:model.live="selectedStatus" class="px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-amber-600 dark:text-amber-400 font-semibold focus:outline-none">
                    <option value="pending">Antrean Pending (In-Review)</option>
                    <option value="active">Anggota Terverifikasi (Active)</option>
                    <option value="all">Semua Data Anggota</option>
                </select>
            </div>
        </div>

        @if($members->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th class="py-3.5 px-4 rounded-l-xl">Kredensial Arsiparis</th>
                        <th class="py-3.5 px-4">Wilayah & Instansi</th>
                        <th class="py-3.5 px-4">Jenjang Fungsional (Verifikator)</th>
                        <th class="py-3.5 px-4">Status & KTA</th>
                        <th class="py-3.5 px-4 text-right rounded-r-xl">Otorisasi Eksekusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @foreach($members as $m)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="py-4 px-4">
                            <div class="font-heading font-extrabold text-slate-900 dark:text-white text-base">{{ $m->name }}</div>
                            <div class="text-xs font-mono text-amber-600 dark:text-amber-400">NIK: {{ $m->masked_nik }} &bull; NIP: {{ $m->masked_nip ?? '-' }}</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $m->user->email ?? '' }} &bull; HP: {{ $m->masked_phone ?? '-' }}</div>
                        </td>
                        <td class="py-4 px-4 text-xs font-medium">
                            <span class="block font-bold text-slate-700 dark:text-slate-200">{{ $m->region->name ?? 'Nasional' }}</span>
                            <span class="block text-slate-500 dark:text-slate-400">{{ $m->institution->name ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-4">
                            @if($m->status->value === 'pending')
                            <select wire:model="memberLevels.{{ $m->id }}" class="bg-slate-50 dark:bg-slate-950 text-amber-600 dark:text-amber-400 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-bold px-3 py-1.5 focus:outline-none">
                                <option value="Ahli Utama">Ahli Utama</option>
                                <option value="Ahli Madya">Ahli Madya</option>
                                <option value="Ahli Muda">Ahli Muda</option>
                                <option value="Terampil">Terampil / Pelaksana</option>
                            </select>
                            @else
                            <span class="font-bold text-blue-400 text-xs">{{ $m->jenjang_arsiparis }} ({{ $m->golongan ?? '-' }})</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($m->status->value === 'active')
                            <div class="space-y-1">
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-extrabold border border-emerald-500/30 uppercase">
                                    {{ $m->status->label() }}
                                </span>
                                <div class="font-mono text-xs font-black text-amber-600 dark:text-amber-400">{{ $m->member_number }}</div>
                            </div>
                            @else
                            <span class="px-2.5 py-1 rounded-full bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 text-[11px] font-bold border border-amber-500/30 uppercase">
                                MENUNGGU APPROVAL
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right">
                            @if($m->status->value === 'pending')
                            <button wire:click="approve({{ $m->id }})" wire:confirm="Setujui pendaftaran dan terbitkan Nomor KTA resmi untuk arsiparis ini?" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-heading font-black text-xs rounded-xl shadow-glow-gold transition">
                                <span wire:loading.remove wire:target="approve({{ $m->id }})">&#10004; Approve & Terbitkan KTA</span>
                                <span wire:loading wire:target="approve({{ $m->id }})">Memverifikasi...</span>
                            </button>
                            @else
                            <a href="{{ route('front.membership.verify', ['q' => $m->member_number]) }}" target="_blank" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700 inline-flex items-center gap-1">
                                <span>Cek E-Card</span> &rarr;
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $members->links() }}
        </div>
        @else
        <div class="py-12 text-center text-slate-400 text-sm">
            Tidak ada data arsiparis dalam antrean atau pencarian ini.
        </div>
        @endif

    </div>

</div>
