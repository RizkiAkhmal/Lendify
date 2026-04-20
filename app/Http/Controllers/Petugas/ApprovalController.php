<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function index()
    {
        $pending = Peminjaman::with(['user', 'alat'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $ready = Peminjaman::with(['user', 'alat'])
            ->where('status', 'approved')
            ->latest()
            ->get();
            
        return view('petugas.approval.index', compact('pending', 'ready'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Status peminjaman bukan pending.');
        }

        // Check stock availability again
        if ($peminjaman->alat->jumlah_tersedia < $peminjaman->jumlah) {
            return back()->with('error', 'Stok alat tidak mencukupi untuk menyetujui peminjaman ini.');
        }

        DB::transaction(function() use ($peminjaman) {
            $peminjaman->update([
                'status' => 'approved',
                'petugas_id' => Auth::id(),
            ]);
            
            // Stock reduction happens once at approval point
            $peminjaman->alat->decrement('jumlah_tersedia', $peminjaman->jumlah);

            \App\Models\LogAktivitas::catat(Auth::id(), 'APPROVE', 'peminjaman', null, $peminjaman->toArray());
        });

        return back()->with('success', 'Peminjaman disetujui. Stok alat telah dikurangi.');
    }

    public function pickup(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'approved') {
            return back()->with('error', 'Status peminjaman bukan approved.');
        }

        // Update status to 'dipinjam' and record actual pickup time
        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_peminjaman' => now(), // Set to actual pickup time
        ]);

        \App\Models\LogAktivitas::catat(Auth::id(), 'PICKUP', 'peminjaman', null, $peminjaman->toArray());

        return back()->with('success', 'Barang berhasil diambil (Status: Dipinjam). Monitoring sekarang fokus pada pengembalian.');
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Status peminjaman bukan pending.');
        }

        $request->validate([
            'catatan_petugas' => 'required|string|max:255',
        ]);

        $peminjaman->update([
            'status' => 'rejected',
            'petugas_id' => Auth::id(),
            'catatan_petugas' => $request->catatan_petugas,
        ]);

        \App\Models\LogAktivitas::catat(Auth::id(), 'REJECT', 'peminjaman', null, $peminjaman->toArray());

        return back()->with('success', 'Peminjaman ditolak.');
    }
}
