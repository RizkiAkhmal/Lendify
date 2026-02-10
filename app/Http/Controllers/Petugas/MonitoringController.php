<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index()
    {
        $approved = Peminjaman::with(['user', 'alat'])
            ->where('status', 'approved')
            ->latest()
            ->get();

        $dipinjam = Peminjaman::with(['user', 'alat'])
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        return view('petugas.monitoring.index', compact('approved', 'dipinjam'));
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

        return back()->with('success', 'Barang berhasil diambil (Status: Dipinjam).');
    }

    public function returnForm(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Status peminjaman bukan dipinjam.');
        }
        
        $denda_keterlambatan = $peminjaman->hitungDendaKeterlambatan();
        $terlambat_hari = $peminjaman->hitungKeterlambatan();

        return view('petugas.monitoring.return', compact('peminjaman', 'denda_keterlambatan', 'terlambat_hari'));
    }

    public function pengembalian(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Status peminjaman bukan dipinjam.');
        }

        $request->validate([
            'kondisi_akhir' => 'required|in:baik,rusak_ringan,rusak_berat',
            'catatan' => 'nullable|string',
        ]);

        $denda_keterlambatan = $peminjaman->hitungDendaKeterlambatan();
        $keterlambatan_hari = $peminjaman->hitungKeterlambatan();
        
        $denda_kerusakan = 0;
        switch ($request->kondisi_akhir) {
            case 'rusak_ringan':
                $denda_kerusakan = 50000;
                break;
            case 'rusak_berat':
                $denda_kerusakan = 200000;
                break;
        }

        $total_denda = $denda_keterlambatan + $denda_kerusakan;

        DB::transaction(function() use ($peminjaman, $request, $total_denda, $keterlambatan_hari) {
            // Create Pengembalian record
            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tanggal_kembali_aktual' => now(),
                'kondisi_alat' => $request->kondisi_akhir,
                'keterlambatan_hari' => $keterlambatan_hari,
                'denda' => $total_denda,
                'catatan' => $request->catatan,
            ]);

            // Update Peminjaman status
            $peminjaman->update([
                'status' => 'selesai', 
                'tanggal_kembali_aktual' => now()
            ]);

            // Increment stock
            $peminjaman->alat->increment('jumlah_tersedia', $peminjaman->jumlah);
            
            // If unique item (count 1), update condition
            if ($peminjaman->alat->jumlah_total == 1) {
                $peminjaman->alat->update(['kondisi' => $request->kondisi_akhir]);
            }

            \App\Models\LogAktivitas::catat(Auth::id(), 'RETURN', 'pengembalian', null, $pengembalian->toArray());
        });

        return redirect()->route('petugas.monitoring.index')->with('success', 'Pengembalian berhasil diproses. Total Denda: Rp ' . number_format($total_denda));
    }
}
