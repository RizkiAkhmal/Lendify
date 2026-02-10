<x-app-layout>
    <x-slot name="header">
        Log Aktivitas Sistem
    </x-slot>

    <div class="card-metronic overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Audit Trails</h3>
                <p class="text-xs text-gray-400">Rekaman seluruh aktivitas perubahan data pada sistem</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Aksi</th>
                        <th class="px-6 py-4">Tabel</th>
                        <th class="px-6 py-4">Meta</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-xs font-semibold text-gray-500">
                                {{ $log->created_at->format('d/m/Y') }}
                                <div class="text-[10px] text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-[#009ef7] flex items-center justify-center font-bold mr-3 text-xs">
                                        {{ substr($log->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $log->user->name ?? 'Unknown' }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">{{ $log->user->role ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $actionColors = [
                                        'CREATE' => 'bg-green-50 text-green-600',
                                        'UPDATE' => 'bg-blue-50 text-blue-600',
                                        'DELETE' => 'bg-red-50 text-red-600',
                                        'LOGIN' => 'bg-purple-50 text-purple-600',
                                    ];
                                    $actionColor = $actionColors[strtoupper($log->aksi)] ?? 'bg-gray-50 text-gray-600';
                                @endphp
                                <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-wider {{ $actionColor }}">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-gray-700 uppercase">{{ $log->tabel }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">
                                <div class="max-w-[200px] truncate" title="{{ $log->ip_address }} | {{ $log->user_agent }}">
                                    IP: {{ $log->ip_address }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Belum ada aktivitas tercatat.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="p-6 bg-white border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
