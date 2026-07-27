@extends('layouts.front')

@section('title', 'Verifikasi Sertifikasi LSP AAI — Asosiasi Arsiparis Indonesia')
@section('meta_description', 'Portal pengecekan dan verifikasi sertifikat kompetensi profesi arsiparis yang diterbitkan oleh LSP / BNSP Asosiasi Arsiparis Indonesia.')

@section('content')
<section class="pt-12 pb-24 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 font-semibold text-xs uppercase tracking-wider border border-blue-500/20">
            Lembaga Sertifikasi Profesi (LSP / BNSP)
        </span>
        <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-slate-900 dark:text-white tracking-tight mt-3 mb-4">
            Verifikasi Sertifikasi Kompetensi
        </h1>
        <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg max-w-xl mx-auto">
            Masukkan Nomor Sertifikat LSP/BNSP (misal: <code class="text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-2 py-0.5 rounded">CERT/LSP/2024/0001</code>) untuk memeriksa legalitas hasil uji kompetensi.
        </p>

        <form action="{{ route('front.certification.verify') }}" method="GET" class="mt-8 max-w-xl mx-auto">
            <div class="flex flex-col sm:flex-row shadow-2xl rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-white/90 dark:bg-slate-900/90 p-1.5">
                <input type="text" name="q" value="{{ $query }}" required
                       placeholder="Masukkan Nomor Sertifikat LSP..."
                       class="flex-grow px-5 py-3.5 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 font-medium text-base focus:outline-none">
                <button type="submit" class="mt-2 sm:mt-0 px-8 py-3.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-400 hover:to-blue-500 text-white font-heading font-extrabold rounded-xl transition shadow-glow-blue flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Cek Sertifikat
                </button>
            </div>
        </form>
    </div>

    @if($query !== '')
        @if($certificate)
        <div class="glass-card rounded-3xl p-8 sm:p-10 border border-blue-500/40 shadow-2xl relative overflow-hidden">
            <div class="absolute top-6 right-6 flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30 text-xs sm:text-sm font-bold tracking-wide">
                KOMPETEN / CERTIFIED
            </div>

            <h2 class="font-heading font-black text-2xl text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                Otoritas Sertifikasi LSP AAI
            </h2>

            <div class="bg-slate-50/80 dark:bg-slate-900/80 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs font-medium">Nomor Sertifikat Resmi</div>
                        <div class="font-mono font-extrabold text-blue-600 dark:text-blue-400 text-lg mt-0.5">{{ $certificate->certificate_number }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs font-medium">Skema Sertifikasi Profesi</div>
                        <div class="font-heading font-bold text-slate-900 dark:text-white text-base mt-0.5">{{ $certificate->scheme->name ?? 'Arsiparis Ahli Muda' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs font-medium">Nama Pemegang Sertifikat</div>
                        <div class="text-slate-800 dark:text-white font-bold mt-0.5">{{ $certificate->participant->member->name ?? 'Anggota Terverifikasi' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs font-medium">Masa Berlaku Kompetensi</div>
                        <div class="text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5">Aktif Hingga {{ \Carbon\Carbon::parse($certificate->expired_at)->format('d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="glass-panel rounded-3xl p-10 text-center border border-red-500/30 max-w-lg mx-auto">
            <h3 class="font-heading font-bold text-xl text-slate-900 dark:text-white">Sertifikat Tidak Ditemukan</h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm mt-2">Nomor sertifikat <strong class="text-blue-600 dark:text-blue-400">{{ $query }}</strong> tidak terdaftar dalam database LSP AAI.</p>
        </div>
        @endif
    @endif
</section>
@endsection
