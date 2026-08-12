<div class="space-y-6">

    @if($noticeMessage)
    <div class="p-4 rounded-2xl bg-indigo-500/15 border border-indigo-500/40 text-indigo-300 text-sm font-bold flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $noticeMessage }}</span>
        </div>
        <button wire:click="$set('noticeMessage', '')" class="text-slate-400 hover:text-white px-2">&times;</button>
    </div>
    @endif

    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Sistem Persuratan E-Office System</span>
                <h2 class="font-heading font-extrabold text-2xl text-slate-900 dark:text-white mt-1">Tata Naskah Dinas & Surat Resmi</h2>
            </div>

            <button wire:click="$set('showCreateModal', true)" class="px-6 py-3 bg-indigo-500 hover:bg-indigo-400 text-white font-heading font-extrabold text-xs rounded-xl transition shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Terbit Naskah Surat Baru</span>
            </button>
        </div>

        @if($letters->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th class="py-3.5 px-4 rounded-l-xl">Nomor Naskah & Tanggal</th>
                        <th class="py-3.5 px-4">Penerima Tujuan</th>
                        <th class="py-3.5 px-4">Perihal / Subject</th>
                        <th class="py-3.5 px-4 text-right rounded-r-xl">Otentikasi QR Hash</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @foreach($letters as $l)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="py-4 px-4 font-mono font-bold text-indigo-600 dark:text-indigo-400 text-sm">
                            {{ $l->letter_number }}
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-sans mt-0.5">{{ \Carbon\Carbon::parse($l->letter_date)->format('d M Y') }}</div>
                        </td>
                        <td class="py-4 px-4 font-heading font-bold text-slate-900 dark:text-white text-base">{{ $l->recipient }}</td>
                        <td class="py-4 px-4 text-slate-600 dark:text-slate-300 text-xs font-semibold">{{ $l->subject }}</td>
                        <td class="py-4 px-4 text-right">
                            <span class="inline-block font-mono text-[10px] text-amber-600 dark:text-amber-400 bg-slate-100 dark:bg-slate-950 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-800 truncate max-w-[140px]">
                                {{ substr($l->qr_code, 0, 16) }}...
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $letters->links() }}
        </div>
        @else
        <div class="py-12 text-center text-slate-500 dark:text-slate-400 text-sm">Belum ada dokumen atau surat dinas terbit.</div>
        @endif
    </div>

    <!-- Modal Buat Surat Baru -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex justify-end">
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-slate-950/80 backdrop-blur-sm" wire:click="$set('showCreateModal', false)"></div>
        <div class="relative w-full md:w-3/4 lg:w-3/4 bg-white dark:bg-slate-900 shadow-2xl flex flex-col h-full animate-slide-in-right overflow-hidden border-l border-slate-200 dark:border-slate-800">
            <h3 class="font-heading font-black text-2xl text-slate-900 dark:text-white">Penerbitan Surat & Tata Naskah</h3>
            <p class="text-slate-500 dark:text-slate-400 text-xs">Penomeran surat otomatis menyesuaikan angka Romawi bulan dan tahun berjalan tanpa bentrok urutan.</p>

            <form wire:submit="createLetter" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Klasifikasi Surat</label>
                        <select wire:model="type_code" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500">
                            <option value="UND">UND - Undangan Resmi</option>
                            <option value="SK">SK - Surat Keputusan</option>
                            <option value="ST">ST - Surat Tugas</option>
                            <option value="KET">KET - Surat Keterangan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pejabat / Instansi Tujuan</label>
                        <input wire:model="recipient" type="text" required placeholder="Kepala ANRI / Gubernur"
                               class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Perihal / Subject Surat</label>
                    <input wire:model="subject" type="text" required placeholder="Undangan Rapat Kerja Nasional (Rakornas) AAI 2024"
                           class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Isi RINGKAS / Inti Instruksi</label>
                    <textarea wire:model="content" rows="4" required placeholder="Menunjuk surat ketetapan No... bersama ini kami mengundang..."
                              class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" wire:click="$set('showCreateModal', false)" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-500 hover:bg-indigo-400 text-white font-heading font-black text-xs rounded-xl shadow">Generate & Terbitkan Naskah &rarr;</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
