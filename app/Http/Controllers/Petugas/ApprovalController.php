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
        $peminjaman = Peminjaman::with(['user', 'alat'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
            
        return view('petugas.approval.index', compact('peminjaman'));
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
            
            // Decrement stock upon approval to reserve the item
            $peminjaman->alat->decrement('jumlah_tersedia', $peminjaman->jumlah);

            \App\Models\LogAktivitas::catat(Auth::id(), 'APPROVE', 'peminjaman', null, $peminjaman->toArray());
        });

        return back()->with('success', 'Peminjaman disetujui. Stok alat dikurangi.');
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
