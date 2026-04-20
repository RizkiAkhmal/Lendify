<x-app-layout>
    <x-slot name="header">
        Manajemen Pengguna
    </x-slot>

    <div class="card-metronic overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3 items-center">
                <div class="relative flex-grow w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#009ef7] text-sm" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>

                <select name="role" class="w-full sm:w-48 px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#009ef7] text-sm cursor-pointer">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="peminjam" {{ request('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                </select>

                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-gray-900 text-white rounded-lg font-bold hover:bg-gray-700 transition text-sm">
                    Filter
                </button>

                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-bold hover:bg-gray-200 transition text-sm text-center">
                        Reset
                    </a>
                @endif

                <a href="{{ route('admin.users.export') }}" class="w-full sm:w-auto px-6 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-sm flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export CSV
                </a>

                <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto px-6 py-2 bg-[#009ef7] text-white rounded-lg font-bold hover:bg-[#0086d1] transition shadow-sm flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Pengguna
                </a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama & Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-500">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-[#009ef7] flex items-center justify-center font-bold mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-red-50 text-red-600',
                                        'petugas' => 'bg-blue-50 text-blue-600',
                                        'peminjam' => 'bg-green-50 text-green-600',
                                    ];
                                    $roleColor = $roleColors[$user->role] ?? 'bg-gray-50 text-gray-600';
                                @endphp
                                <span class="px-3 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $roleColor }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider bg-green-50 text-green-600">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center space-x-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-lg bg-gray-50 text-gray-400 hover:text-[#009ef7] hover:bg-blue-50 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="p-6 bg-white border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
