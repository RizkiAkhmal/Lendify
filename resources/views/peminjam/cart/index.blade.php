<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Keranjang Peminjaman') }}
            </h2>
            <!-- <a href="{{ route('peminjam.katalog.index') }}" class="text-sm text-[#009ef7] hover:underline font-medium">Lanjut Cari Alat</a> -->
        </div>
    </x-slot>

    <div class="py-12">
        {{-- Flash Messages --}}

        @if($errors->any())
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm">
                    <p class="font-bold mb-1">Terdapat kesalahan pada form:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex space-x-4">
                <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-xl flex-1">
                    <p class="text-xs text-indigo-400 font-bold uppercase tracking-wider mb-1">Total Max Peminjaman</p>
                    <p class="text-2xl font-black text-indigo-900">3 Unit</p>
                </div>
                <div class="bg-yellow-50 border border-yellow-100 p-4 rounded-xl flex-1">
                    <p class="text-xs text-yellow-400 font-bold uppercase tracking-wider mb-1">Sedang Dipinjam</p>
                    <p class="text-2xl font-black text-yellow-900">{{ $active_rentals }} Unit</p>
                </div>
                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex-1">
                    <p class="text-xs text-blue-400 font-bold uppercase tracking-wider mb-1">Isi Keranjang</p>
                    @php
                        $cart_total = 0;
                        foreach($cart as $item) $cart_total += $item['jumlah'];
                    @endphp
                    <p class="text-2xl font-black text-blue-900">{{ $cart_total }} Unit</p>
                </div>
                <div class="bg-green-50 border border-green-100 p-4 rounded-xl flex-1">
                    <p class="text-xs text-green-400 font-bold uppercase tracking-wider mb-1">Sisa Kuota</p>
                    <p class="text-2xl font-black text-green-900">{{ max(0, 3 - $active_rentals - $cart_total) }} Unit</p>
                </div>
            </div>

        <!-- Headers and Title are unchanged above -->

            @if(empty($cart))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-16 text-center border border-gray-100">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mb-2">Keranjang Anda Kosong</h3>
                    <p class="text-gray-500 mb-8 max-w-sm mx-auto">Silakan lihat katalog kami untuk menemukan alat yang Anda butuhkan.</p>
                    <a href="{{ route('peminjam.katalog.index') }}" class="px-8 py-3 bg-[#009ef7] text-white rounded-xl font-bold shadow-lg shadow-[#009ef7]/30 hover:bg-[#007bbf] transition">
                        Lihat Katalog Alat
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cart as $id => $item)
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                            <div class="pl-2 pr-4">
                                <input type="checkbox" name="selected_items[]" value="{{ $id }}" form="checkout-form" class="item-checkbox w-6 h-6 rounded border-gray-300 text-[#009ef7] focus:ring-[#009ef7] cursor-pointer" data-qty="{{ $item['jumlah'] }}" checked>
                            </div>
                            @if($item['foto'])
                                <img src="{{ Storage::url($item['foto']) }}" class="w-24 h-24 rounded-xl object-cover border border-gray-100 shadow-sm" alt="{{ $item['nama_alat'] }}">
                            @else
                                <div class="w-24 h-24 rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded-md">{{ $item['kode_alat'] }}</span>
                                <h4 class="text-lg font-bold text-gray-900 mt-1 mb-0.5">{{ $item['nama_alat'] }}</h4>
                                <p class="text-xs text-gray-500 mb-3">{{ $item['merk'] ?? 'Tanpa Merk' }}</p>
                            </div>
                            <div class="text-center px-6 border-l border-r border-gray-100">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Jumlah</p>
                                <p class="text-xl font-black text-gray-800">{{ $item['jumlah'] }}</p>
                            </div>
                            <div class="pl-2">
                                <form id="delete-form-cart-{{ $id }}" action="{{ route('peminjam.cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="p-3 text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm" onclick="confirmDelete('cart-{{ $id }}', 'Hapus alat ini dari keranjang?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Checkout Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
                            <h3 class="text-lg font-black text-gray-900 mb-4 border-b border-gray-100 pb-3">Formulir Peminjaman</h3>
                            <form id="checkout-form" action="{{ route('peminjam.cart.checkout') }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tgl Pinjam (Ambil)</label>
                                        <input type="date" id="tanggal_peminjaman" name="tanggal_peminjaman" class="w-full rounded-lg border-gray-300 focus:border-[#009ef7] focus:ring-[#009ef7] text-sm" value="{{ old('tanggal_peminjaman', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                        @error('tanggal_peminjaman') <span class="text-xs text-red-600 block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Rencana Kembali</label>
                                        <input type="date" id="tanggal_kembali_rencana" name="tanggal_kembali_rencana" class="w-full rounded-lg border-gray-300 focus:border-[#009ef7] focus:ring-[#009ef7] text-sm" value="{{ old('tanggal_kembali_rencana') }}" min="{{ date('Y-m-d') }}" required>
                                        @error('tanggal_kembali_rencana') <span class="text-xs text-red-600 block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keperluan Penggunaan</label>
                                        <textarea name="keperluan" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#009ef7] focus:ring-[#009ef7] text-sm resize-none" placeholder="Alasan peminjaman" required>{{ old('keperluan') }}</textarea>
                                        @error('keperluan') <span class="text-xs text-red-600 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="bg-indigo-50 text-indigo-800 p-4 rounded-xl text-xs font-medium border border-indigo-100 mt-2 leading-relaxed">
                                        Total yang dipilih: <strong id="selected-qty" class="text-indigo-900 border-b border-indigo-300 pb-0.5 text-sm">0 Unit</strong>
                                    </div>

                                    @error('selected_items')
                                        <span class="text-xs text-red-600 block">{{ $message }}</span>
                                    @enderror

                                    <button type="submit" id="checkout-btn"
                                        onclick="this.disabled=true; this.innerHTML='Memproses...'; this.form.submit();"
                                        class="w-full py-3.5 mt-2 bg-[#1e1e2d] hover:bg-black text-white font-bold rounded-xl shadow-lg transition disabled:opacity-60 disabled:cursor-not-allowed">
                                        Ajukan Peminjaman
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Script for Checkbox Validation -->
    @if(!empty($cart))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Checkbox and Quota logic
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const selectedQtyDisplay = document.getElementById('selected-qty');
            const maxQuota = {{ max(0, 3 - $active_rentals) }};
            
            function updateSelected() {
                let currentSelectedQty = 0;
                checkboxes.forEach(chk => {
                    if (chk.checked) currentSelectedQty += parseInt(chk.dataset.qty);
                });
                selectedQtyDisplay.textContent = currentSelectedQty + ' Unit';
            }

            checkboxes.forEach(chk => {
                chk.addEventListener('click', function(e) {
                    let futureQty = 0;
                    checkboxes.forEach(c => {
                        if (c.checked) futureQty += parseInt(c.dataset.qty);
                    });

                    if (futureQty > maxQuota) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Batas Maksimal',
                            text: 'Maksimal total peminjaman Anda adalah ' + maxQuota + ' barang.',
                            icon: 'error',
                            confirmButtonColor: '#1e1e2d',
                            confirmButtonText: 'Oke'
                        });
                        this.checked = false;
                    } else {
                        updateSelected();
                    }
                });
            });

            // Date validation logic
            const tglPinjam = document.getElementById('tanggal_peminjaman');
            const tglKembali = document.getElementById('tanggal_kembali_rencana');

            if (tglPinjam && tglKembali) {
                tglPinjam.addEventListener('change', function() {
                    tglKembali.min = this.value;
                    if (tglKembali.value && tglKembali.value < this.value) {
                        tglKembali.value = this.value;
                    }
                });

                // Set initial min for tglKembali
                if (tglPinjam.value) {
                    tglKembali.min = tglPinjam.value;
                }
            }

            // Initial selection logic
            let initialQty = 0;
            checkboxes.forEach(chk => {
                let qty = parseInt(chk.dataset.qty);
                if (initialQty + qty <= maxQuota) {
                    chk.checked = true;
                    initialQty += qty;
                } else {
                    chk.checked = false;
                }
            });
            updateSelected();
        });

        function changeQty(id, delta) {
            const qtyElement = document.getElementById('qty-' + id);
            const checkbox = document.querySelector(`.item-checkbox[value="${id}"]`);
            let currentQty = parseInt(qtyElement.textContent);
            let newQty = currentQty + delta;

            if (newQty < 1) return;

            // AJAX Update
            fetch(`/peminjam/cart/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ jumlah: newQty })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    qtyElement.textContent = newQty;
                    checkbox.dataset.qty = newQty;
                    
                    // Update header cart badges if they exist
                    let cartBadges = document.querySelectorAll('.cart-badge');
                    cartBadges.forEach(badge => {
                        badge.textContent = data.cartCount;
                    });

                    // Trigger updateSelected to recalculate totals
                    const selectedQtyDisplay = document.getElementById('selected-qty');
                    const checkboxes = document.querySelectorAll('.item-checkbox');
                    let currentSelectedQty = 0;
                    checkboxes.forEach(chk => {
                        if (chk.checked) currentSelectedQty += parseInt(chk.dataset.qty);
                    });
                    selectedQtyDisplay.textContent = currentSelectedQty + ' Unit';

                    // Optional: Validation check again if selection now exceeds quota
                    const maxQuota = {{ max(0, 3 - $active_rentals) }};
                    if (currentSelectedQty > maxQuota) {
                        checkbox.checked = false;
                        // Recalculate
                        currentSelectedQty = 0;
                        checkboxes.forEach(chk => {
                            if (chk.checked) currentSelectedQty += parseInt(chk.dataset.qty);
                        });
                        selectedQtyDisplay.textContent = currentSelectedQty + ' Unit';
                        
                        Swal.fire({
                            title: 'Kuota Terlampaui',
                            text: 'Pembaruan jumlah menyebabkan pilihan Anda melebihi kuota 3 barang.',
                            icon: 'warning',
                            confirmButtonColor: '#1e1e2d'
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Gagal',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#f1416c'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
    @endif
</x-app-layout>
