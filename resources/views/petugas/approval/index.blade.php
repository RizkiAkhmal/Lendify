<x-app-layout>
    <x-slot name="header">
        Antrean Persetujuan
    </x-slot>

    <div class="card-metronic overflow-hidden">
    <div class="space-y-8">
        <!-- 1. Menunggu Persetujuan -->
        <div class="card-metronic overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Menunggu Persetujuan</h3>
                    <p class="text-xs text-gray-400">Daftar permintaan peminjaman baru</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Alat & Stok</th>
                            <th class="px-6 py-4">Keperluan</th>
                            <th class="px-6 py-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($pending as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $item->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800">{{ $item->alat->nama_alat }}</div>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">REQ: {{ $item->jumlah }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-600 italic">"{{ $item->keperluan }}"</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        @if($item->alat->jumlah_tersedia >= $item->jumlah)
                                            <form id="approve-form-{{ $item->id }}" action="{{ route('petugas.approval.approve', $item) }}" method="POST">
                                                @csrf
                                                <button type="button" onclick="confirmSubmit('approve-form-{{ $item->id }}', 'Setujui Peminjaman?', 'Stok alat akan otomatis berkurang setelah disetujui.')" class="bg-green-500 text-white px-3 py-1 rounded text-[10px] font-bold uppercase hover:bg-green-600 transition">
                                                    SETUJUI
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="bg-gray-300 text-gray-500 px-3 py-1 rounded text-[10px] font-bold cursor-not-allowed">
                                                STOK KURANG
                                            </button>
                                        @endif

                                        <form id="reject-form-{{ $item->id }}" action="{{ route('petugas.approval.reject', $item) }}" method="POST" class="hidden">
                                            @csrf
                                            <input type="hidden" name="catatan_petugas" id="catatan-{{ $item->id }}">
                                        </form>
                                        <button type="button" onclick="promptReject({{ $item->id }})" class="bg-red-50 text-red-600 px-3 py-1 rounded text-[10px] font-bold uppercase hover:bg-red-500 hover:text-white transition border border-red-200">
                                            TOLAK
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400">Tidak ada pengajuan baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Siap Diambil (Approved) -->
        <div class="card-metronic overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
                <div>
                    <h3 class="text-lg font-bold text-[#009ef7]">Siap Diambil</h3>
                    <p class="text-xs text-gray-400">Persetujuan selesai, menunggu pengambilan barang</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Alat</th>
                            <th class="px-6 py-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($ready as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->user->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800">{{ $item->alat->nama_alat }} ({{ $item->jumlah }})</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form id="pickup-form-{{ $item->id }}" action="{{ route('petugas.approval.pickup', $item) }}" method="POST">
                                        @csrf
                                        <button type="button" onclick="confirmSubmit('pickup-form-{{ $item->id }}', 'Konfirmasi Pengambilan?', 'apakah anda yakin?')" class="bg-[#009ef7] text-white px-4 py-1.5 rounded text-[10px] font-bold uppercase hover:bg-blue-600 shadow-sm transition">
                                            Barang Diambil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400">Tidak ada barang menunggu pengambilan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <script>
        function promptReject(id) {
            Swal.fire({
                title: 'Alasan Penolakan',
                input: 'textarea',
                inputPlaceholder: 'TAlasan penolakan',
                inputAttributes: {
                    'aria-label': 'Alasan penolakan'
                },
                showCancelButton: true,
                confirmButtonColor: '#f1416c',
                cancelButtonColor: '#f1f1f1',
                confirmButtonText: 'Tolak Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    title: 'text-lg font-bold text-gray-800',
                    input: 'text-sm rounded-lg border-gray-200 focus:ring-[#f1416c] focus:border-[#f1416c]',
                    confirmButton: 'px-6 py-2 rounded-md font-semibold text-white text-xs uppercase tracking-wider',
                    cancelButton: 'px-6 py-2 rounded-md font-semibold text-gray-600 text-xs uppercase tracking-wider'
                },
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Alasan penolakan wajib diisi!')
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('catatan-' + id).value = result.value;
                    document.getElementById('reject-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
