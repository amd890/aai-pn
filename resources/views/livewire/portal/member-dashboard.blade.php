<div class="space-y-8" x-data="{ showPrintModal: false }">

    @if($paymentNotice)
    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-bold flex items-center justify-between shadow-lg animate-fade">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $paymentNotice }}</span>
        </div>
        <button wire:click="$set('paymentNotice', '')" class="text-slate-400 hover:text-white font-bold px-2">&times;</button>
    </div>
    @endif

    @if(!$member)
    <div class="bg-white/80 dark:bg-slate-900 backdrop-blur-md rounded-3xl p-10 border border-amber-500/30 text-center max-w-xl mx-auto my-12 shadow-2xl">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-500 mx-auto flex items-center justify-center text-2xl mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="font-heading font-black text-2xl text-slate-900 dark:text-white">Data Keanggotaan Belum Ditemukan</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 leading-relaxed">
            Akun Anda saat ini bertingkat pengguna regular. Untuk mengaktifkan fasilitas Kartu Tanda Anggota dan E-Office AAI, silakan daftarkan kredensial profesi Anda.
        </p>
        <a href="{{ route('register') }}" class="mt-6 inline-block px-8 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 font-heading font-black text-slate-950 rounded-xl shadow-glow-gold">
            Daftar Anggota AAI Sekarang &rarr;
        </a>
    </div>
    @else
    
    <!-- Status Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900/90 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status Anggota</span>
                <div class="mt-2 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full {{ $member->status->value === 'active' ? 'bg-emerald-500 shadow-glow-emerald' : 'bg-amber-500' }}"></span>
                    <span class="font-heading font-extrabold text-xl text-slate-900 dark:text-white uppercase">{{ $member->status->label() }}</span>
                </div>
            </div>
            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-[10px] font-mono font-bold text-amber-600 dark:text-amber-400">
                {{ $member->member_number ?? 'IN-REVIEW' }}
            </span>
        </div>

        <div class="bg-white dark:bg-slate-900/90 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-xl flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jenjang & Golongan</span>
            <div class="mt-2 font-heading font-bold text-lg text-blue-600 dark:text-blue-400 truncate">
                {{ $member->jenjang_arsiparis }} ({{ $member->golongan ?? '-' }})
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900/90 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-xl flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Wilayah / Pengurus Daerah</span>
            <div class="mt-2 font-heading font-bold text-lg text-slate-900 dark:text-white truncate">
                {{ $member->region->name ?? 'Nasional / Pusat' }}
            </div>
        </div>
    </div>

    <!-- Section: KTA Digital E-Card & Instant Verification -->
    <div class="bg-white dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-950 rounded-3xl p-6 sm:p-10 border border-amber-500/30 dark:border-amber-500/40 shadow-2xl relative overflow-hidden">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 relative z-10">
            
            <div class="max-w-lg space-y-4">
                <span class="px-3 py-1 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30 rounded-full text-xs font-bold uppercase tracking-wider">
                    Fasilitas Anggota Resmi
                </span>
                <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white">
                    Kartu Tanda Anggota Digital
                </h2>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                    KTA ini merupakan identitas resmi profesi arsiparis yang sah di seluruh wilayah Republik Indonesia, terintegrasi langsung dengan kode otentikasi QR kriptografis AAI Nasional.
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    @if($member->card)
                    <button @click="showPrintModal = true" class="px-6 py-3 bg-amber-500 hover:bg-amber-400 text-white dark:text-slate-950 font-heading font-extrabold text-xs rounded-xl transition shadow-glow-gold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Unduh & Cetak KTA (PDF / Web)</span>
                    </button>
                    <a href="{{ route('front.membership.verify', ['q' => $member->member_number]) }}" target="_blank" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-white font-semibold text-xs rounded-xl transition border border-slate-200 dark:border-slate-700 flex items-center gap-2">
                        <span>Lihat Bukti Verifikasi Publik &rarr;</span>
                    </a>
                    @else
                    <span class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700">
                        KTA Sedang Menunggu Verifikasi Pengurus
                    </span>
                    @endif
                </div>
            </div>

            <!-- Replica Card Visual -->
            <div class="w-full sm:w-[380px] aspect-[1.586/1] rounded-2xl bg-gradient-to-br from-slate-900 via-slate-950 to-indigo-950 border-2 border-amber-500/50 p-6 flex flex-col justify-between shadow-2xl shrink-0 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center font-heading font-black text-slate-950 text-xs shadow">
                            AAI
                        </div>
                        <div>
                            <span class="block font-heading font-bold text-xs text-white uppercase">Asosiasi Arsiparis Indonesia</span>
                            <span class="block text-[9px] text-amber-400 uppercase tracking-widest font-semibold">KTA Digital Resmi</span>
                        </div>
                    </div>
                </div>

                <div class="my-auto relative z-10 pt-4 grid grid-cols-3 gap-2">
                    <div class="col-span-2">
                        <div class="text-[10px] text-slate-400 font-semibold uppercase">Nomor Keanggotaan</div>
                        <div class="font-mono font-extrabold text-lg text-amber-400">{{ $member->member_number ?? 'AAI-XXX-WAIT' }}</div>
                        <div class="font-heading font-extrabold text-white text-base truncate mt-0.5">{{ $member->name }}</div>
                        <div class="text-[10px] font-semibold text-blue-300">{{ $member->position }}</div>
                    </div>
                    <div class="flex justify-end items-center flex-col">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white p-1.5 rounded-lg border border-amber-500 flex items-center justify-center text-center overflow-hidden shadow">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(64)->margin(0)->generate(route('front.membership.verify', ['q' => $member->member_number ?? 'AAI-XXX-WAIT'])) !!}
                        </div>
                        <span class="text-[8px] text-amber-400 font-mono font-semibold mt-1">SCAN VERIFY</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-800 flex justify-between text-[10px] text-slate-400 relative z-10">
                    <span>Wilayah: <strong class="text-white">{{ $member->region->name ?? 'Nasional' }}</strong></span>
                    <span>Valid: <strong class="text-emerald-400">Lifetime Active</strong></span>
                </div>
            </div>

        </div>
    </div>

    <!-- Section: Iuran Wajib Tahunan & Pembayaran Reaktif -->
    <div class="bg-white dark:bg-slate-900/70 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h3 class="font-heading font-bold text-xl text-slate-900 dark:text-white">Tagihan Iuran Wajib Keanggotaan</h3>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-1">Pembayaran iuran mendukung berbagai diklat serta asuransi perlindungan hukum arsiparis</p>
            </div>
            
            <div class="flex items-center gap-2 text-xs bg-slate-50 dark:bg-slate-950 p-1 rounded-xl border border-slate-200 dark:border-slate-800">
                <button wire:click="$set('selectedMethod', 'virtual_account')" class="px-3 py-1.5 rounded-lg font-bold transition {{ $selectedMethod === 'virtual_account' ? 'bg-amber-500 text-white dark:text-slate-950 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                    Simulasi Virtual Account (VA)
                </button>
                <button wire:click="$set('selectedMethod', 'bank_transfer')" class="px-3 py-1.5 rounded-lg font-bold transition {{ $selectedMethod === 'bank_transfer' ? 'bg-slate-200 dark:bg-slate-800 text-amber-600 dark:text-amber-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                    Transfer Bank / Manual
                </button>
            </div>
        </div>

        @if($dues && $dues->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">Tahun / Periode</th>
                        <th class="py-3 px-4">Nominal Iuran</th>
                        <th class="py-3 px-4">Jatuh Tempo</th>
                        <th class="py-3 px-4">Status Tagihan</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($dues as $due)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="py-4 px-4 font-heading font-bold text-slate-900 dark:text-white">Iuran Wajib AAI Tahun {{ $due->period_year }}</td>
                        <td class="py-4 px-4 font-mono font-extrabold text-amber-600 dark:text-amber-400">Rp {{ number_format($due->amount, 0, ',', '.') }}</td>
                        <td class="py-4 px-4 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($due->due_date)->format('d M Y') }}</td>
                        <td class="py-4 px-4">
                            @if($due->status->value === 'paid')
                            <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 rounded-full text-xs font-bold">
                                LUNAS TERVERIFIKASI
                            </span>
                            @elseif($due->status->value === 'pending')
                            <span class="px-2.5 py-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 rounded-full text-xs font-bold">
                                MENUNGGU PEMBAYARAN
                            </span>
                            @else
                            <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-full text-xs font-semibold border border-slate-200 dark:border-slate-700">
                                {{ strtoupper($due->status->value ?? $due->status) }}
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right">
                            @if($due->status->value === 'pending')
                            <button wire:click="simulatePayment({{ $due->id }})" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white dark:text-slate-950 font-heading font-bold text-xs rounded-xl transition shadow">
                                {{ $selectedMethod === 'virtual_account' ? 'Bayar Lunas (VA Simulation)' : 'Submit Bukti Transfer' }}
                            </button>
                            @else
                            <span class="text-xs font-mono text-slate-500">INVOICE TERSEDIA</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-8 text-center text-sm text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border border-slate-200 dark:border-slate-800">
            Tidak ada tagihan Iuran Wajib tertagih untuk saat ini.
        </div>
        @endif
    </div>

    <!-- Section: Riwayat Invoice & Kwitansi Resmi -->
    @if($invoices && $invoices->count() > 0)
    <div class="bg-white dark:bg-slate-900/70 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-xl space-y-4">
        <h3 class="font-heading font-bold text-xl text-slate-900 dark:text-white">Riwayat Invoice & Bukti Pembayaran Resmi</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($invoices as $inv)
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 hover:border-amber-500/40 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-2">
                        <span class="font-mono">{{ \Carbon\Carbon::parse($inv->created_at)->format('d M Y') }}</span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold uppercase">{{ $inv->status }}</span>
                    </div>
                    <div class="font-mono font-black text-amber-600 dark:text-amber-400 text-base">{{ $inv->invoice_number }}</div>
                    <div class="text-xs font-semibold text-slate-600 dark:text-slate-300 mt-1">Metode: {{ strtoupper(str_replace('_', ' ', $inv->payment_method)) }}</div>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-200 dark:border-slate-900 flex justify-between items-center text-xs">
                    <span class="font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($inv->amount, 0, ',', '.') }}</span>
                    <button @click="alert('Mengunduh Invoice Elektronik PDF: ' + '{{ $inv->invoice_number }}')" class="text-amber-600 dark:text-amber-400 font-bold hover:underline flex items-center gap-1">
                        <span>Unduh PDF</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @endif

    <!-- Modal Cetak KTA -->
    <div x-show="showPrintModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="showPrintModal = false" class="glass-card rounded-3xl p-8 max-w-lg w-full border border-amber-500/40 shadow-2xl text-center space-y-6">
            <h3 class="font-heading font-black text-2xl text-white">Pradeep C. KTA Digital</h3>
            <p class="text-slate-400 text-xs leading-relaxed">
                Kartu Tanda Anggota Anda siap dicetak atau disimpan ke perangkat mudah alih (Mobile Wallet) Anda.
            </p>
            <div class="p-6 bg-white text-slate-950 rounded-2xl font-mono text-left text-xs space-y-2 border-4 border-slate-900">
                <div class="font-black text-sm text-center border-b pb-2">ASOSIASI ARSIPARIS INDONESIA</div>
                <div>NO KTA: <span class="font-bold">{{ $member?->member_number }}</span></div>
                <div>NAMA: <span class="font-bold">{{ $member?->name }}</span></div>
                <div>JENJANG: <span class="font-bold">{{ $member?->jenjang_arsiparis }}</span></div>
                <div>WILAYAH: <span class="font-bold">{{ $member?->region?->name }}</span></div>
                <div class="text-[9px] text-center pt-2 text-slate-500">AUTHENTICATED BY BNSP & AAI SYSTEM</div>
            </div>
            <div class="flex gap-3 justify-center">
                <button @click="window.print(); showPrintModal = false" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs rounded-xl shadow">Cetak Sekarang (Print)</button>
                <button @click="showPrintModal = false" class="px-6 py-2.5 bg-slate-800 text-slate-300 text-xs rounded-xl">Tutup Window</button>
            </div>
        </div>
    </div>

</div>
