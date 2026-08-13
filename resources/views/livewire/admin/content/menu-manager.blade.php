<div>
    <!-- Header -->
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pengaturan Navigasi & Menu</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola tautan menu pada Header dan Footer portal publik.</p>
        </div>
        <button wire:click="createMenu" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Menu
        </button>
    </div>

    <!-- Menus Grouped by Location -->
    <div class="space-y-8">
        @php
            $locations = [
                'header' => 'Header Navigasi Utama',
                'footer_media' => 'Footer: Media & Publikasi',
                'footer_services' => 'Footer: Layanan Publik',
                'footer_external' => 'Footer: Tautan Eksternal'
            ];
        @endphp

        @foreach($locations as $locationKey => $locationName)
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $locationName }}</h2>
            </div>
            
            <div class="p-0">
                @if(isset($menus[$locationKey]) && $menus[$locationKey]->count() > 0)
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/20 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-3 font-medium w-16 text-center">Urutan</th>
                            <th class="px-6 py-3 font-medium">Label Menu</th>
                            <th class="px-6 py-3 font-medium">URL Tujuan</th>
                            <th class="px-6 py-3 font-medium text-center">Target</th>
                            <th class="px-6 py-3 font-medium text-center">Status</th>
                            <th class="px-6 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($menus[$locationKey] as $index => $menu)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-center gap-1">
                                    @if(!$loop->first)
                                    <button wire:click="moveUp({{ $menu->id }})" class="text-slate-400 hover:text-amber-600 dark:hover:text-amber-400" title="Geser ke Atas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                    @endif
                                    <span class="text-xs font-mono font-bold text-slate-600 dark:text-slate-300">{{ $menu->order }}</span>
                                    @if(!$loop->last)
                                    <button wire:click="moveDown({{ $menu->id }})" class="text-slate-400 hover:text-amber-600 dark:hover:text-amber-400" title="Geser ke Bawah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">
                                {{ $menu->label }}
                            </td>
                            <td class="px-6 py-4 font-mono text-sm text-amber-600 dark:text-amber-400">
                                {{ $menu->url }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-slate-600 dark:text-slate-400">
                                {{ $menu->target === '_blank' ? 'Tab Baru' : 'Tab Sama' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($menu->is_active)
                                <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-xs font-semibold">Aktif</span>
                                @else
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-xs font-semibold">Sembunyi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="editMenu({{ $menu->id }})" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">Edit</button>
                                <button wire:click="deleteMenu({{ $menu->id }})" wire:confirm="Hapus menu ini?" class="text-red-600 dark:text-red-400 hover:underline text-sm font-medium">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="px-6 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">
                    Belum ada menu di lokasi ini.
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal Form Menu -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex justify-start">
        <div class="absolute inset-0 bg-slate-900/50 dark:bg-slate-950/80 backdrop-blur-sm" wire:click="$set('isModalOpen', false)"></div>
        
        <div class="relative w-full md:w-3/4 lg:w-3/4 bg-white dark:bg-slate-900 shadow-2xl flex flex-col h-full animate-slide-in-left overflow-hidden border-r border-slate-200 dark:border-slate-800">
                
                <form wire:submit.prevent="saveMenu">
                    <div class="px-6 py-5 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ $modalMode === 'create' ? 'Tambah Menu Baru' : 'Edit Menu' }}
                        </h3>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Label Menu</label>
                            <input type="text" wire:model="label" required class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm" placeholder="Contoh: Tentang Kami">
                            @error('label') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipe Tautan</label>
                            <select wire:model.live="linkType" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm">
                                <option value="custom">URL Kustom</option>
                                <option value="page">Halaman (Page)</option>
                                <option value="article">Artikel / Berita</option>
                                <option value="category">Kategori Artikel</option>
                            </select>
                        </div>

                        @if($linkType !== 'custom')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pilih Tujuan</label>
                            <select wire:model.live="selectedItemId" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm">
                                <option value="">-- Pilih --</option>
                                @if($linkType === 'page')
                                    @foreach($pages as $page)
                                        <option value="{{ $page->id }}">{{ $page->title }}</option>
                                    @endforeach
                                @elseif($linkType === 'article')
                                    @foreach($articles as $article)
                                        <option value="{{ $article->id }}">{{ $article->title }}</option>
                                    @endforeach
                                @elseif($linkType === 'category')
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">URL Tujuan</label>
                            <input type="text" wire:model="url" required 
                                @if($linkType !== 'custom') readonly @endif
                                class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm font-mono @if($linkType !== 'custom') bg-slate-100 dark:bg-slate-800 cursor-not-allowed @endif" placeholder="Contoh: /about atau https://...">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Gunakan path relatif (misal `/about`) untuk halaman internal.</p>
                            @error('url') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Lokasi</label>
                                <select wire:model="location" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm">
                                    <option value="header">Header Utama</option>
                                    <option value="footer_media">Footer: Media & Publikasi</option>
                                    <option value="footer_services">Footer: Layanan Publik</option>
                                    <option value="footer_external">Footer: Tautan Eksternal</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Target</label>
                                <select wire:model="target" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm">
                                    <option value="_self">Tab Saat Ini (_self)</option>
                                    <option value="_blank">Tab Baru (_blank)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 cursor-pointer mt-2">
                                <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Menu Aktif (Ditampilkan)</span>
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex justify-start gap-3">
                        <button type="button" wire:click="$set('isModalOpen', false)" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-amber-600 border border-transparent rounded-lg shadow-sm hover:bg-amber-700 transition-colors">
                            Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
