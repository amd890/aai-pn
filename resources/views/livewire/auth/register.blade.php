<div class="pt-12 pb-24 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="glass-card rounded-3xl p-8 sm:p-12 border border-slate-800 shadow-2xl relative">
        
        @if($registered)
        <!-- Registration Success Notification & Next Steps -->
        <div class="text-center py-10">
            <div class="w-20 h-20 bg-emerald-500/10 dark:bg-emerald-500/20 border border-emerald-500/30 dark:border-emerald-500/40 text-emerald-600 dark:text-emerald-400 rounded-full mx-auto flex items-center justify-center mb-6 shadow-lg">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="font-heading font-black text-3xl text-slate-900 dark:text-white">Registrasi Berhasil Diajukan!</h1>
            <p class="text-slate-500 dark:text-slate-300 text-base max-w-xl mx-auto mt-3 leading-relaxed">
                Terima kasih telah berpartisipasi dalam transformasi digital kearsipan nasional. Akun dan berkas Anda saat ini berada dalam status <strong class="text-amber-600 dark:text-amber-400">PENDING VERIFICATION</strong>.
            </p>
            <div class="bg-white/90 dark:bg-slate-900/90 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 max-w-lg mx-auto my-8 text-left text-xs text-slate-600 dark:text-slate-400 space-y-2 shadow-sm">
                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold">
                    <span>1. Pengajuan Diterima oleh Sistem</span>
                </div>
                <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400 font-bold">
                    <span>2. Pemeriksaan Dokumen oleh Verifikator & Dewan Wilayah</span>
                </div>
                <div class="flex items-center gap-2 text-slate-500">
                    <span>3. Penerbitan KTA Digital & Tagihan Iuran Tahunan</span>
                </div>
            </div>
            <a href="{{ route('login') }}" class="px-8 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-heading font-bold rounded-xl shadow-glow-gold hover:scale-105 transition inline-block">
                Masuk ke Portal Anggota &rarr;
            </a>
        </div>
        @else
        <!-- Reactive Registration Form -->
        <div class="text-center mb-10">
            <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 font-bold text-xs uppercase tracking-wider border border-amber-500/20">
                Pendaftaran Arsiparis Nasional
            </span>
            <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-3">Registrasi Keanggotaan & KTA</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">Lengkapi kredensial profesi untuk memperoleh Kartu Tanda Anggota Digital AAI</p>
        </div>

        <form wire:submit="register" class="space-y-6">
            <!-- Section 1: Identitas Pribadi -->
            <div class="bg-white/60 dark:bg-slate-900/60 p-6 rounded-2xl border border-slate-200 dark:border-slate-800/80 space-y-4 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 pb-2 border-b border-slate-200 dark:border-slate-800">1. Data Pribadi & Kontak</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap (Beserta Gelar)</label>
                        <input wire:model="name" type="text" required placeholder="Dr. Budi Arsiparis, S.Sos., M.AP."
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                        @error('name') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Induk Kependudukan (16 Digit NIK)</label>
                        <input wire:model="nik" type="text" required maxlength="16" placeholder="3273030101850001"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-mono text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                        @error('nik') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Induk Pegawai (NIP / Opsional)</label>
                        <input wire:model="nip" type="text" placeholder="198501012010011001"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-mono text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                        @error('nip') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Telepon / WhatsApp Aktif</label>
                        <input wire:model="phone" type="text" placeholder="081234567890"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                        @error('phone') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Kredensial Profesi & Wilayah -->
            <div class="bg-white/60 dark:bg-slate-900/60 p-6 rounded-2xl border border-slate-200 dark:border-slate-800/80 space-y-4 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 pb-2 border-b border-slate-200 dark:border-slate-800">2. Kredensial Kearsipan & Wilayah Kerja</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pengurus Wilayah / Provinsi</label>
                        <select wire:model="region_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($regions as $r)
                                <option value="{{ $r->id }}">{{ $r->name }} (Kode: {{ $r->code }})</option>
                            @endforeach
                        </select>
                        @error('region_id') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Institusi Tempat Kerja / Dinas</label>
                        <select wire:model="institution_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                            @endforeach
                        </select>
                        @error('institution_id') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Jenjang Jabatan Fungsional</label>
                        <select wire:model="jenjang_arsiparis" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                            <option value="Ahli Utama">Arsiparis Ahli Utama</option>
                            <option value="Ahli Madya">Arsiparis Ahli Madya</option>
                            <option value="Ahli Muda">Arsiparis Ahli Muda</option>
                            <option value="Terampil">Arsiparis Terampil / Pelaksana</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pangkat / Golongan</label>
                        <input wire:model="golongan" type="text" placeholder="IV/c atau III/d"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Akun Login Portal -->
            <div class="bg-white/60 dark:bg-slate-900/60 p-6 rounded-2xl border border-slate-200 dark:border-slate-800/80 space-y-4 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 pb-2 border-b border-slate-200 dark:border-slate-800">3. Kredensial Akun Portal Member</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Email (Digunakan untuk Login Portal)</label>
                        <input wire:model="email" type="email" required placeholder="nama@arsip.go.id"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                        @error('email') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi (Min. 6 Karakter)</label>
                            <input wire:model="password" type="password" required placeholder="••••••••"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                            @error('password') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Ulangi Kata Sandi</label>
                            <input wire:model="password_confirmation" type="password" required placeholder="••••••••"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-heading font-extrabold text-base rounded-xl shadow-glow-gold transition duration-200 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="register">Kirim Permohonan & Daftar KTA &rarr;</span>
                    <span wire:loading wire:target="register">Membuat Akun & Mendaftarkan Kredensial...</span>
                </button>
            </div>

            <p class="text-center text-xs text-slate-500 mt-4">
                Dengan mendaftar, Anda menyatakan bahwa seluruh data kearsipan di atas adalah otentik dan mematuhi <a href="{{ route('front.terms') }}" class="text-slate-400 underline">Anggaran Dasar & Rumah Tangga AAI</a>.
            </p>
        </form>
        @endif

    </div>
</div>
