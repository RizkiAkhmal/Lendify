<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peminjaman Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Peminjam Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Peminjam</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="font-semibold mb-2">{{ $peminjaman->user->name }}</p>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold mb-2">{{ $peminjaman->user->email }}</p>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-semibold">{{ $peminjaman->user->phone ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Alat Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Alat</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Nama Alat</p>
                                <p class="font-semibold mb-2">{{ $peminjaman->alat->nama_alat }}</p>
                                <p class="text-sm text-gray-600">Kode Alat</p>
                                <p class="font-semibold mb-2">{{ $peminjaman->alat->kode_alat }}</p>
                                <p class="text-sm text-gray-600">Kategori</p>
                                <p class="font-semibold">{{ $peminjaman->alat->kategori->nama_kategori }}</p>
                            </div>
                        </div>

                        <!-- Transaction Info -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Peminjaman</h3>
                            <div class="bg-gray-50 p-4 rounded-lg grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal Pinjam</p>
                                    <p class="font-semibold">{{ $peminjaman->tanggal_peminjaman ? $peminjaman->tanggal_peminjaman->format('d M Y') : '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Rencana Kembali</p>
                                    <p class="font-semibold">{{ $peminjaman->tanggal_kembali_rencana }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal Kembali Aktual</p>
                                    <p class="font-semibold">{{ $peminjaman->pengembalian->tanggal_kembali_aktual ? $peminjaman->pengembalian->tanggal_kembali_aktual->format('d M Y') : '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $peminjaman->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $peminjaman->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $peminjaman->status === 'dipinjam' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $peminjaman->status === 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $peminjaman->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-sm text-gray-600">Keterangan/Keperluan</p>
                                    <p class="font-semibold">{{ $peminjaman->keperluan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('admin.peminjaman.invoice', $peminjaman) }}" class="px-5 py-2 bg-[#009ef7] text-white rounded-md font-bold hover:bg-[#0086d1] transition shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Print Invoice
                        </a>
                        <a href="{{ route('admin.peminjaman.index') }}" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-md font-bold hover:bg-gray-300 transition">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
