<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['alat', 'pengembalian'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('peminjam.peminjaman.index', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alat,id',
            'tanggal_peminjaman' => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_peminjaman',
            'jumlah' => 'required|integer|min:1',
            'keperluan' => 'required|string|max:255',
        ]);

        $alat = Alat::findOrFail($request->alat_id);

        if ($request->jumlah > $alat->jumlah_tersedia) {
            return back()->withErrors(['jumlah' => 'Jumlah permintaan melebihi stok tersedia.']);
        }

        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'alat_id' => $request->alat_id,
            'tanggal_pengajuan' => now(),
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'jumlah' => $request->jumlah,
            'keperluan' => $request->keperluan,
            'status' => 'pending',
        ]);

        \App\Models\LogAktivitas::catat(Auth::id(), 'REQUEST', 'peminjaman', null, $peminjaman->toArray());

        return redirect()->route('peminjam.peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan petugas.');
    }
}
