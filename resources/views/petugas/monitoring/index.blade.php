<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="space-y-8">
                <!-- Sedang Dipinjam -->
                <div class="card-metronic overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Sedang Dipinjam</h3>
                            <p class="text-xs text-gray-400">Daftar alat yang sedang aktif digunakan</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                                    <th class="px-6 py-4">Peminjam</th>
                                    <th class="px-6 py-4">Alat</th>
                                    <th class="px-6 py-4">Tgl Kembali</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($dipinjam as $item)
                                    @php
                                        $telat = $item->hitungKeterlambatan();
                                    @endphp
                                    <tr class="{{ $telat > 0 ? 'bg-red-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->alat->nama_alat }} ({{ $item->jumlah }})</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->tanggal_kembali_rencana->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($telat > 0)
                                                <span class="text-red-600 font-bold">Telat {{ $telat }} hari</span>
                                            @else
                                                <span class="text-green-600">On Schedule</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('petugas.monitoring.return.form', $item) }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                                Proses Kembali
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada barang yang sedang dipinjam.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
