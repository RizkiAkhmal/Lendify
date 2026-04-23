<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $active_rentals = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'dipinjam'])
            ->sum('jumlah');

        return view('peminjam.cart.index', compact('cart', 'active_rentals'));
    }

    public function add(Request $request, Alat $alat)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $jumlah = $request->jumlah;
        
        if ($jumlah > $alat->jumlah_tersedia) {
            return back()->with('error', 'Jumlah permintaan melebihi stok yang tersedia.');
        }

        $cart = session()->get('cart', []);
        
        // The cart acts as a temporary container. No limit for putting into cart. Limit is enforced at checkout.

        if(isset($cart[$alat->id])) {
            $cart[$alat->id]['jumlah'] += $jumlah;
        } else {
            $cart[$alat->id] = [
                'id'          => $alat->id,
                'nama_alat'   => $alat->nama_alat,
                'kode_alat'   => $alat->kode_alat,
                'merk'        => $alat->merk,
                'jumlah'      => $jumlah,
                'max_stok'    => $alat->jumlah_tersedia,
                'foto'        => $alat->foto
            ];
        }

        session()->put('cart', $cart);
        
        if ($request->ajax()) {
            $cartCount = array_sum(array_column($cart, 'jumlah'));
            return response()->json([
                'success'   => true, 
                'message'   => 'Barang berhasil ditambahkan ke keranjang.',
                'cartCount' => $cartCount
            ]);
        }
        
        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Barang dihapus dari keranjang.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        
        if(!isset($cart[$id])) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan dalam keranjang.']);
        }

        $alat = Alat::find($id);
        if (!$alat) {
            return response()->json(['success' => false, 'message' => 'Alat tidak ditemukan.']);
        }

        if ($request->jumlah > $alat->jumlah_tersedia) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi.']);
        }

        $cart[$id]['jumlah'] = $request->jumlah;
        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'jumlah'));
        return response()->json([
            'success'   => true, 
            'message'   => 'Jumlah berhasil diperbarui.',
            'cartCount' => $cartCount
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'tanggal_peminjaman'      => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_peminjaman',
            'keperluan'               => 'required|string|max:255',
            'selected_items'          => 'required|array|min:1',
            'selected_items.*'        => 'integer|exists:alat,id',
        ], [
            'tanggal_peminjaman.required'      => 'Tanggal peminjaman wajib diisi.',
            'tanggal_peminjaman.after_or_equal'=> 'Tanggal peminjaman tidak boleh sebelum hari ini.',
            'tanggal_kembali_rencana.required' => 'Tanggal rencana kembali wajib diisi.',
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali harus setelah atau sama dengan tanggal peminjaman.',
            'keperluan.required'               => 'Keperluan penggunaan wajib diisi.',
            'selected_items.required'          => 'Pilih minimal 1 barang untuk di-checkout.',
            'selected_items.min'               => 'Pilih minimal 1 barang untuk di-checkout.',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('peminjam.cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Cast selected IDs to integer
        $selected_ids = array_map('intval', $request->selected_items);

        $checkout_total = 0;
        $items_to_checkout = [];
        foreach ($selected_ids as $id) {
            if (isset($cart[$id])) {
                $checkout_total += $cart[$id]['jumlah'];
                $items_to_checkout[$id] = $cart[$id];
            }
        }

        if (empty($items_to_checkout)) {
            return back()->with('error', 'Tidak ada barang valid yang dipilih untuk checkout.');
        }

        $active_rentals = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'dipinjam'])
            ->sum('jumlah');

        if (($checkout_total + $active_rentals) > 3) {
            return back()->with('error', "Maksimal peminjaman adalah 3 unit. Pilihan Anda ({$checkout_total} unit) melebihi batas (Aktif: {$active_rentals} unit).");
        }

        try {
            \DB::beginTransaction();

            $created = [];
            foreach ($items_to_checkout as $id => $item) {
                // Final stock check before creating records
                $alat = Alat::find($id);
                if (!$alat || $alat->jumlah_tersedia < $item['jumlah']) {
                    throw new \Exception('Stok alat "' . $item['nama_alat'] . '" tidak mencukupi saat ini.');
                }

                $peminjaman = Peminjaman::create([
                    'user_id'                 => Auth::id(),
                    'alat_id'                 => $id,
                    'tanggal_pengajuan'       => now()->toDateString(), // Use toDateString for DATE column
                    'tanggal_peminjaman'      => $request->tanggal_peminjaman,
                    'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                    'jumlah'                  => $item['jumlah'],
                    'keperluan'               => $request->keperluan,
                    'status'                  => 'pending',
                ]);

                \App\Models\LogAktivitas::catat(Auth::id(), 'REQUEST', 'peminjaman', null, $peminjaman->toArray());
                
                // Remove checked-out item from cart array
                unset($cart[$id]);
            }

            // Save remaining cart items
            session()->put('cart', $cart);

            \DB::commit();

            return redirect()->route('peminjam.peminjaman.index')
                ->with('success', 'Checkout berhasil! Pengajuan Anda sedang menunggu persetujuan petugas.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }
    }
}
