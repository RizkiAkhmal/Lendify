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
                                    <p class="font-semibold">{{ $peminjaman->tanggal_pinjam }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Rencana Kembali</p>
                                    <p class="font-semibold">{{ $peminjaman->tanggal_kembali_rencana }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal Kembali Aktual</p>
                                    <p class="font-semibold">{{ $peminjaman->tanggal_kembali_aktual ?? '-' }}</p>
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
                                    <p class="text-sm text-gray-600">Keterangan</p>
                                    <p class="font-semibold">{{ $peminjaman->keterangan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Action -->
                        <div class="md:col-span-2 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Action</h3>
                            <form action="{{ route('admin.peminjaman.update', $peminjaman) }}" method="POST" class="flex items-end gap-4">
                                @csrf
                                @method('PUT')
                                <div class="w-full md:w-1/3">
                                    <x-input-label for="status" :value="__('Change Status Manually')" />
                                    <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="pending" {{ $peminjaman->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $peminjaman->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                        <option value="selesai" {{ $peminjaman->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="rejected" {{ $peminjaman->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <x-primary-button>Update Status</x-primary-button>
                            </form>
                            <p class="text-xs text-red-500 mt-2">* Warning: Changing status manually here might bypass some stock validation logic. Use with caution.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
