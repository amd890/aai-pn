<div>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-black text-slate-900 dark:text-white">System Audit & Activity Logs</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Pemantauan rekam jejak aktivitas pengguna dan perubahan data</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="log_name" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-slate-300 text-sm focus:border-amber-500 outline-none">
                <option value="">Semua Modul</option>
                @foreach($logNames as $name)
                    <option value="{{ $name }}">{{ ucfirst($name) }}</option>
                @endforeach
            </select>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari aktivitas..." class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-slate-300 text-sm focus:border-amber-500 outline-none w-64">
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900/30 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden backdrop-blur-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/80 text-xs uppercase font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="py-4 px-6 w-48">Waktu (WIB)</th>
                        <th class="py-4 px-6 w-32">Modul</th>
                        <th class="py-4 px-6">Pelaku / Causer</th>
                        <th class="py-4 px-6 w-1/3">Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-mono text-xs">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3 px-6 text-slate-500 dark:text-slate-400">
                            {{ $log->created_at->format('d M Y, H:i:s') }}
                        </td>
                        <td class="py-3 px-6">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-amber-600 dark:text-amber-400 border border-slate-200 dark:border-slate-700 uppercase">{{ $log->log_name ?? 'System' }}</span>
                        </td>
                        <td class="py-3 px-6">
                            @if($log->causer)
                                <div class="font-bold text-slate-900 dark:text-white font-sans">{{ $log->causer->name }}</div>
                                <div class="text-[10px] text-slate-500">ID: {{ $log->causer_id }}</div>
                            @else
                                <span class="text-slate-500 italic">System / Anonymous</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-slate-600 dark:text-slate-300">
                            <span class="{{ str_contains(strtolower($log->description), 'delete') ? 'text-red-600 dark:text-red-400' : (str_contains(strtolower($log->description), 'create') ? 'text-emerald-600 dark:text-emerald-400' : 'text-blue-600 dark:text-blue-400') }}">
                                {{ $log->description }}
                            </span>
                            @if($log->subject_id)
                                <div class="mt-1 text-[10px] text-slate-500">Target: {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-500 font-sans font-medium">Belum ada rekam log aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
            {{ $logs->links(data: ['scrollTo' => false]) }}
        </div>
        @endif
    </div>
</div>
