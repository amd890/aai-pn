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

    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Otoritas Bendahara Nasional</span>
                <h2 class="font-heading font-extrabold text-2xl text-slate-900 dark:text-white mt-1">Verifikasi Bukti Transfer & Kas Iuran</h2>
            </div>
        </div>

        @if($payments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th class="py-3.5 px-4 rounded-l-xl">Nomor Invoice & Tanggal</th>
                        <th class="py-3.5 px-4">Metode Bayar</th>
                        <th class="py-3.5 px-4">Nominal Tagihan</th>
                        <th class="py-3.5 px-4">Status Otorisasi</th>
                        <th class="py-3.5 px-4 text-right rounded-r-xl">Aksi Bendahara</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @foreach($payments as $p)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="py-4 px-4">
                            <div class="font-mono font-black text-amber-600 dark:text-amber-400 text-base">{{ $p->invoice?->invoice_number ?? ('PAY-' . str_pad($p->id, 6, '0', STR_PAD_LEFT)) }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dibuat: {{ \Carbon\Carbon::parse($p->created_at)->format('d F Y') }}</div>
                        </td>
                        <td class="py-4 px-4 text-xs font-bold text-slate-700 dark:text-slate-200 uppercase">
                            {{ str_replace('_', ' ', ($p->method->value ?? $p->method ?? 'bank_transfer')) }}
                        </td>
                        <td class="py-4 px-4 font-mono font-extrabold text-slate-900 dark:text-white text-base">
                            Rp {{ number_format($p->amount, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-4">
                            @if(($p->status->value ?? $p->status) === 'verified' || ($p->status->value ?? $p->status) === 'paid' || ($p->status->value ?? $p->status) === 'success')
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-extrabold text-[11px] border border-emerald-500/30 uppercase">
                                LUNAS TERVERIFIKASI
                            </span>
                            @elseif(($p->status->value ?? $p->status) === 'pending')
                            <span class="px-2.5 py-1 rounded-full bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 font-extrabold text-[11px] border border-amber-500/30 uppercase animate-pulse">
                                MENUNGGU OTORISASI BENDAHARA
                            </span>
                            @else
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-300 text-xs font-semibold uppercase">
                                {{ $p->status->value ?? $p->status }}
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right">
                            @if(($p->status->value ?? $p->status) === 'pending')
                            <button wire:click="verifyPayment({{ $p->id }})" wire:confirm="Otorisasi bukti transfer bank dan nyatakan Invoice ini terverifikasi lunas?" class="px-5 py-2.5 bg-blue-500 hover:bg-blue-400 text-white font-heading font-black text-xs rounded-xl transition shadow">
                                &#10004; Verifikasi Lunas
                            </button>
                            @else
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">AUTHORIZED</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
        @else
        <div class="py-12 text-center text-slate-500 dark:text-slate-400 text-sm">Belum ada riwayat transaksi atau pembayaran terselesaikan.</div>
        @endif
    </div>

</div>
