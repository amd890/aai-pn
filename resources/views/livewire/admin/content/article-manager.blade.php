<div>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-black text-slate-900 dark:text-white">Content Management System</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola Berita, Opini Kearsipan Digital, dan Publikasi Ilmiah Kearsipan</p>
        </div>
        <button wire:click="createNew" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 font-bold text-sm rounded-xl transition shadow-glow-gold flex items-center gap-2">
            <span>+</span> Tulis Artikel Baru
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
                        <th class="py-4 px-6">Judul Artikel</th>
                        <th class="py-4 px-6">Tipe & Kategori</th>
                        <th class="py-4 px-6">Status Publikasi</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($articles as $article)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900 dark:text-white mb-1">{{ $article->title }}</div>
                            <div class="text-xs text-slate-500">Ditulis oleh: {{ $article->author->name ?? 'Admin' }} | View: {{ $article->view_count }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase border border-slate-200 dark:border-slate-700">
                                {{ $article->type->value ?? 'News' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if(($article->status->value ?? $article->status) === 'published')
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 font-bold text-xs border border-emerald-200 dark:border-emerald-500/30 uppercase">
                                    Published
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold text-xs border border-slate-200 dark:border-slate-700 uppercase">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <button wire:click="edit({{ $article->id }})" class="text-amber-600 dark:text-amber-400 hover:text-amber-500 dark:hover:text-amber-300 font-medium text-xs uppercase tracking-wide">Edit</button>
                            <button wire:click="delete({{ $article->id }})" wire:confirm="Yakin ingin menghapus artikel ini?" class="text-red-600 dark:text-red-400 hover:text-red-500 dark:hover:text-red-300 font-medium text-xs uppercase tracking-wide">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-500 font-medium">Belum ada artikel yang dipublikasikan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($articles->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/30">
            {{ $articles->links(data: ['scrollTo' => false]) }}
        </div>
        @endif
    </div>

    <!-- Article Form Modal -->
    @if($showFormModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-slate-950/80 backdrop-blur-sm" wire:click="$set('showFormModal', false)"></div>
        <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 shrink-0">
                <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">{{ $isEditMode ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h3>
                <button wire:click="$set('showFormModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 min-h-0">
                <form wire:submit="save" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="md:col-span-2 space-y-5">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Judul Artikel</label>
                                <input wire:model="title" type="text" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 transition" placeholder="Masukkan judul memikat...">
                                @error('title') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Ringkasan (Excerpt)</label>
                                <textarea wire:model="excerpt" rows="2" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 transition" placeholder="Ringkasan singkat artikel..."></textarea>
                                @error('excerpt') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Konten (Markdown/HTML)</label>
                                <textarea wire:model="content" rows="12" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white font-mono text-sm focus:outline-none focus:border-amber-500 transition custom-scrollbar" placeholder="Ketik konten artikel di sini..."></textarea>
                                @error('content') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Tipe Konten</label>
                                <select wire:model="type" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 transition">
                                    <option value="news">Berita (News)</option>
                                    <option value="article">Opini Kearsipan Digital / Artikel</option>
                                    <option value="agenda">Agenda AAI</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Status Publikasi</label>
                                <select wire:model="status" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 transition">
                                    <option value="draft">Draft (Konsep)</option>
                                    <option value="published">Publish Sekarang</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Gambar Sampul</label>
                                <div class="border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-amber-500 dark:hover:border-amber-500 rounded-xl p-6 flex flex-col items-center justify-center text-center transition bg-slate-50 dark:bg-slate-900/50">
                                    <input type="file" wire:model="cover_image" class="hidden" id="coverUpload">
                                    <label for="coverUpload" class="cursor-pointer flex flex-col items-center">
                                        <svg class="w-8 h-8 text-slate-400 dark:text-slate-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-amber-600 dark:text-amber-400 font-semibold text-sm">Pilih Gambar</span>
                                        <span class="text-slate-500 text-xs mt-1">PNG, JPG up to 2MB</span>
                                    </label>
                                </div>
                                @if($cover_image)
                                    <p class="text-emerald-600 dark:text-emerald-400 text-xs mt-2 font-medium">✓ File terpilih: {{ $cover_image->getClientOriginalName() }}</p>
                                @endif
                                @error('cover_image') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3 shrink-0">
                <button type="button" wire:click="$set('showFormModal', false)" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl transition border border-slate-200 dark:border-slate-700 shadow-sm">Batal</button>
                <!-- Because the button is outside the form, we trigger the form save via JS or wire:click -->
                <button type="button" wire:click="save" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white dark:text-slate-950 font-bold text-sm rounded-xl transition shadow-glow-emerald">
                    Simpan Artikel
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
