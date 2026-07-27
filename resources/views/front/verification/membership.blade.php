@extends('layouts.front')

@section('title', 'Verifikasi Keanggotaan & KTA Digital — Asosiasi Arsiparis Indonesia')
@section('meta_description', 'Portal Otentikasi dan Verifikasi Keaslian Kartu Tanda Anggota (KTA) Digital serta Status Kearsipan Asosiasi Arsiparis Indonesia secara realtime.')

@section('content')
<section class="pt-12 pb-24 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 font-semibold text-xs uppercase tracking-wider border border-amber-500/20">
            Sistem Keamanan & Otentikasi Digital
        </span>
        <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-slate-900 dark:text-white tracking-tight mt-3 mb-4">
            Verifikasi Anggota & KTA Digital
        </h1>
        <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg max-w-2xl mx-auto">
            Masukkan Nomor Anggota AAI (misal: <code class="text-amber-600 dark:text-amber-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-2 py-0.5 rounded">AAI-TEST-0001</code>) atau Nomor KTA / Kode QR untuk memeriksa keaslian data arsiparis secara langsung.
        </p>

        <!-- Interactive Lookup Box -->
        <form action="{{ route('front.membership.verify') }}" method="GET" class="mt-8 max-w-xl mx-auto">
            <div class="flex flex-col sm:flex-row shadow-2xl rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-white/90 dark:bg-slate-900/90 p-1.5">
                <input type="text" name="q" value="{{ $query }}" required
                       placeholder="Masukkan Nomor Anggota / Nomor Kartu..."
                       class="flex-grow px-5 py-3.5 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 font-medium text-base focus:outline-none">
                <button type="submit" class="mt-2 sm:mt-0 px-8 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-heading font-extrabold rounded-xl transition shadow-glow-gold flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Verifikasi Data
                </button>
            </div>
        </form>
    </div>

    <!-- Results Area -->
    @if($query !== '')
        @if($member)
        <div class="glass-card rounded-3xl p-8 sm:p-10 border border-amber-500/40 shadow-2xl relative overflow-hidden" x-data="{ viewMode: 'card' }">
            
            <!-- Verified Stamp Badge -->
            <div class="absolute top-6 right-6 flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs sm:text-sm font-bold tracking-wide">
                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                TERVERIFIKASI RESMI (ACTIVE)
            </div>

            <h2 class="font-heading font-black text-2xl text-slate-900 dark:text-white mb-8 flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                Hasil Otentikasi Arsiparis
            </h2>

            <!-- Digital KTA Mockup Replica (E-Card) -->
            <div class="mb-12 max-w-lg mx-auto">
                <div class="w-full aspect-[1.586/1] rounded-2xl bg-gradient-to-br from-slate-900 via-slate-950 to-indigo-950 border-2 border-amber-500/50 p-6 sm:p-8 flex flex-col justify-between shadow-glow-gold relative overflow-hidden group">
                    
                    <!-- Decorative background pattern -->
                    <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute top-0 right-0 p-6 opacity-15 font-heading font-black text-8xl tracking-tighter text-amber-500 select-none pointer-events-none">AAI</div>

                    <!-- Card Header -->
                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center font-heading font-black text-slate-950 text-base shadow">
                                AAI
                            </div>
                            <div>
                                <span class="block font-heading font-bold text-sm text-white uppercase tracking-wide">Asosiasi Arsiparis Indonesia</span>
                                <span class="block text-[10px] font-semibold text-amber-400 uppercase tracking-widest">Kartu Tanda Anggota Digital</span>
                            </div>
                        </div>
                        <span class="text-[11px] font-mono text-slate-400 border border-slate-700 px-2.5 py-0.5 rounded-md bg-slate-900/80">
                            {{ $card?->card_number ?? 'CARD-ACTIVE' }}
                        </span>
                    </div>

                    <!-- Card Body / Member Info -->
                    <div class="grid grid-cols-3 gap-4 items-center my-auto relative z-10 pt-4">
                        <div class="col-span-2 space-y-1.5">
                            <div class="text-xs text-slate-400 uppercase font-semibold">Nomor Anggota Resmi</div>
                            <div class="font-mono text-xl sm:text-2xl font-extrabold text-amber-400 tracking-wider">
                                {{ $member->member_number }}
                            </div>
                            <div class="font-heading font-extrabold text-lg sm:text-xl text-white truncate pt-1">
                                {{ $member->name }}
                            </div>
                            <div class="text-xs font-semibold text-blue-300">
                                {{ $member->position ?? 'Arsiparis' }} &bull; {{ $member->jenjang_arsiparis ?? 'Ahli' }}
                            </div>
                        </div>

                        <!-- QR Hash Representation -->
                        <div class="flex flex-col items-end justify-center">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-white p-2 rounded-xl border border-amber-500 shadow-md flex items-center justify-center flex-col text-center overflow-hidden">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->margin(0)->generate(route('front.membership.verify', ['q' => $card?->qr_code ?? 'QR-OK-999'])) !!}
                            </div>
                            <span class="text-[9px] text-amber-400 font-mono font-semibold mt-1">SCAN VERIFY</span>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-800/80 text-[11px] text-slate-400 relative z-10">
                        <span>Wilayah: <strong class="text-slate-200">{{ $member->region->name ?? 'Indonesia' }}</strong></span>
                        <span>Berlaku Hingga: <strong class="text-amber-400">{{ $card ? \Carbon\Carbon::parse($card->expired_at)->format('m/Y') : 'Lifetime' }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Detailed Privacy-Masked Information Table -->
            <div class="bg-slate-50/80 dark:bg-slate-900/70 rounded-2xl p-6 border border-slate-200 dark:border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-4 pb-2 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <span>Rincian Data Keanggotaan</span>
                    <span class="text-[10px] text-amber-600 dark:text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded">Privasi NIK & No HP Dilindungi (Masking)</span>
                </h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 font-medium">Nama Lengkap Arsiparis</dt>
                        <dd class="text-slate-900 dark:text-white font-heading font-bold text-base mt-0.5">{{ $member->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 font-medium">Institusi / Tempat Kerja</dt>
                        <dd class="text-slate-800 dark:text-white font-semibold mt-0.5">{{ $member->institution->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 font-medium">Jenjang Fungsional & Golongan</dt>
                        <dd class="text-slate-800 dark:text-white font-semibold mt-0.5">{{ $member->jenjang_arsiparis ?? '-' }} ({{ $member->golongan ?? '-' }})</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 font-medium">Pengurus Wilayah / Daerah</dt>
                        <dd class="text-slate-800 dark:text-white font-semibold mt-0.5">{{ $member->region->name ?? 'Nasional' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 font-medium">Nomor Induk Kependudukan (NIK)</dt>
                        <dd class="font-mono text-amber-600 dark:text-amber-400 font-bold mt-0.5">{{ $member->masked_nik ?? '3273XXXXXXXX0001' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 font-medium">Nomor Induk Pegawai (NIP)</dt>
                        <dd class="font-mono text-slate-700 dark:text-slate-200 font-bold mt-0.5">{{ $member->masked_nip ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-6 text-center text-xs text-slate-500">
                Otentikasi ini dihasilkan langsung secara sistem berbasis tanda tangan kriptografis dari database resmi Asosiasi Arsiparis Indonesia.
            </div>
        </div>
        @else
        <div class="glass-panel rounded-3xl p-12 text-center border border-red-500/30 max-w-2xl mx-auto shadow-2xl">
            <div class="w-16 h-16 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-500 mx-auto flex items-center justify-center text-2xl mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="font-heading font-bold text-2xl text-slate-900 dark:text-white">Data Tidak Ditemukan</h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm mt-2 leading-relaxed">
                Nomor Anggota atau KTA <strong class="text-amber-600 dark:text-amber-400">{{ $query }}</strong> tidak terdaftar atau belum diverifikasi oleh Dewan Pengurus Nasional AAI.
            </p>
            <div class="mt-6">
                <a href="{{ route('front.membership.verify') }}" class="inline-block px-6 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-white font-semibold text-xs border border-slate-200 dark:border-slate-700 transition">
                    Coba Pencarian Lain &rarr;
                </a>
            </div>
        </div>
        @endif
    @endif
</section>
@endsection
