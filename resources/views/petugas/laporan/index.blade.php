<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form method="GET" action="{{ route('petugas.laporan.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Filter</button>
                            <a href="{{ route('petugas.laporan.index') }}" class="ml-2 text-gray-600 hover:text-gray-900">Reset</a>
                        </div>
                        <div class="ml-auto">
                            <!-- TODO: Fix route call for export if needed -->
                            <!-- <a href="{{ route('petugas.laporan.export', request()->all()) }}" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">Export CSV</a> -->
                        </div>
                    </form>
                </div>
                
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terlambat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Denda</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($laporan as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->alat->nama_alat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->pengembalian->tanggal_kembali_aktual ? $item->pengembalian->tanggal_kembali_aktual->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ ($item->pengembalian->keterlambatan_hari ?? 0) > 0 ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                            {{ $item->pengembalian->keterlambatan_hari ?? 0 }} hari
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp {{ number_format($item->pengembalian->denda ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                            {{ str_replace('_', ' ', $item->pengembalian->kondisi_alat ?? '-') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data laporan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $laporan->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
