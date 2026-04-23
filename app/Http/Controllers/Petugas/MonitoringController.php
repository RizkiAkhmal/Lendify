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
        $dipinjam = Peminjaman::with(['user', 'alat'])
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        return view('petugas.monitoring.index', compact('dipinjam'));
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
            'denda_kerusakan' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $denda_keterlambatan = $peminjaman->hitungDendaKeterlambatan();
        $keterlambatan_hari = $peminjaman->hitungKeterlambatan();
        
        $denda_kerusakan = $request->denda_kerusakan;

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

            // Stock management based on condition
            if ($request->kondisi_akhir === 'baik') {
                $peminjaman->alat->increment('jumlah_tersedia', $peminjaman->jumlah);
            } else {
                $peminjaman->alat->increment('jumlah_rusak', $peminjaman->jumlah);
            }
            
            // 'kondisi' field is completely removed, rely only on jumlah calculations

            \App\Models\LogAktivitas::catat(Auth::id(), 'RETURN', 'pengembalian', null, $pengembalian->toArray());
        });

        return redirect()->route('petugas.monitoring.index')->with('success', 'Pengembalian berhasil diproses. Total Denda: Rp ' . number_format($total_denda));
    }
}
