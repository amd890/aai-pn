<div class="space-y-8">

    <!-- Executive KPI Counters Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-950 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Anggota Aktif KTA</span>
                <div class="mt-2 font-heading font-black text-3xl text-emerald-600 dark:text-emerald-400">{{ number_format($totalActiveMembers, 0, ',', '.') }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">Ahli</span></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-950 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Antrean Verifikasi</span>
                <div class="mt-2 font-heading font-black text-3xl text-amber-600 dark:text-amber-400">{{ $totalPendingMembers }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">Berkas</span></div>
            </div>
            <a href="{{ route('admin.members.verification-queue') }}" class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </a>
        </div>

        <div class="bg-white dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-950 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Akumulasi Iuran & Kas</span>
                <div class="mt-2 font-heading font-black text-2xl text-slate-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-950 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Naskah Dinas Terbit</span>
                <div class="mt-2 font-heading font-black text-3xl text-indigo-600 dark:text-indigo-400">{{ $totalLetters }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">Surat</span></div>
            </div>
            <a href="{{ route('admin.correspondence.out.index') }}" class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 hover:bg-indigo-500/20 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </a>
        </div>
    </div>

    <!-- Quick Action Hub -->
    <div class="bg-white/80 dark:bg-slate-900/70 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-xl">
        <h2 class="font-heading font-black text-lg text-slate-900 dark:text-white mb-4">Meja Kerja Operasional Pengurus AAI</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.members.verification-queue') }}" class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 hover:border-amber-500 dark:hover:border-amber-500/50 hover:shadow-lg hover:shadow-amber-500/10 transition-all group flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 font-black flex items-center justify-center text-lg mb-3 ring-1 ring-amber-500/30">1</div>
                    <h3 class="font-heading font-bold text-slate-900 dark:text-white text-base group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Verifikasi & Approval KTA</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1.5 leading-relaxed">Periksa berkas ijazah, tetapkan jenjang fungsional, dan terbitkan nomor KTA Digital resmi.</p>
                </div>
                <div class="mt-5 text-xs font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1">Buka Antrean Kerja &rarr;</div>
            </a>

            <a href="{{ route('admin.finance.payments.index') }}" class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 hover:border-blue-500 dark:hover:border-blue-500/50 hover:shadow-lg hover:shadow-blue-500/10 transition-all group flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 font-black flex items-center justify-center text-lg mb-3 ring-1 ring-blue-500/30">2</div>
                    <h3 class="font-heading font-bold text-slate-900 dark:text-white text-base group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Verifikasi Kas & Iuran Wajib</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1.5 leading-relaxed">Otoritas Bendahara: validasi bukti bayar bank transfer anggota dan otentikasi faktur Invoice.</p>
                </div>
                <div class="mt-5 text-xs font-bold text-blue-600 dark:text-blue-400 flex items-center gap-1">Kelola Keuangan AAI &rarr;</div>
            </a>

            <a href="{{ route('admin.correspondence.out.index') }}" class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 hover:border-indigo-500 dark:hover:border-indigo-500/50 hover:shadow-lg hover:shadow-indigo-500/10 transition-all group flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 font-black flex items-center justify-center text-lg mb-3 ring-1 ring-indigo-500/30">3</div>
                    <h3 class="font-heading font-bold text-slate-900 dark:text-white text-base group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">E-Office Tata Naskah Dinas</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1.5 leading-relaxed">Penerbitan surat keputusan dan undangan resmi dengan penomor Romawi otomatis ber-QR.</p>
                </div>
                <div class="mt-5 text-xs font-bold text-indigo-600 dark:text-indigo-400 flex items-center gap-1">Buat Surat Dinas &rarr;</div>
            </a>
        </div>
    </div>

    <!-- Tables and Data Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Members List -->
        <div class="lg:col-span-2 bg-white/80 dark:bg-slate-900/70 backdrop-blur-md rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-xl flex flex-col justify-between">
            <div>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-4 flex items-center justify-between">
                    <span>Pendaftar & Anggota Terbaru</span>
                    <a href="{{ route('admin.members.verification-queue') }}" class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:underline">Lihat Semua &rarr;</a>
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead class="text-xs uppercase text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800 font-bold">
                            <tr>
                                <th class="py-3 px-3">Arsiparis</th>
                                <th class="py-3 px-3">Wilayah</th>
                                <th class="py-3 px-3">Status</th>
                                <th class="py-3 px-3 text-right">No KTA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            @foreach($recentMembers as $m)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3 px-3 font-medium text-slate-900 dark:text-white">
                                    {{ $m->name }}
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $m->institution->name ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-3 text-xs text-slate-600 dark:text-slate-300">{{ $m->region->name ?? 'Nasional' }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->status->value === 'active' ? 'bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400' }}">
                                        {{ $m->status->label() }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-mono text-xs font-bold text-amber-600 dark:text-amber-400 text-right">{{ $m->member_number ?? 'IN-REVIEW' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Regional Distribution KPI -->
        <div class="bg-white/80 dark:bg-slate-900/70 backdrop-blur-md rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-xl flex flex-col justify-between">
            <div>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-4">Sebaran Arsiparis Wilayah</h3>
                <div class="space-y-3">
                    @foreach($regions as $reg)
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="block font-heading font-bold text-sm text-slate-900 dark:text-white">{{ $reg->name }}</span>
                            <span class="block text-[10px] text-slate-500 dark:text-slate-400 uppercase font-mono">Kode Wilayah: {{ $reg->code }}</span>
                        </div>
                        <div class="px-3 py-1 rounded-xl bg-amber-500/10 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 font-extrabold font-mono text-base border border-amber-500/20">
                            {{ $reg->members_count }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</div>
