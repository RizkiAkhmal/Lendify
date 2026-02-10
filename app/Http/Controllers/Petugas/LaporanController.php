<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat', 'pengembalian'])
            ->where('status', 'selesai')
            ->latest();

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal_peminjaman', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal_peminjaman', '<=', $request->end_date);
        }

        $laporan = $query->paginate(20);

        return view('petugas.laporan.index', compact('laporan'));
    }

    public function export(Request $request)
    {
        // Simple CSV Export
        $query = Peminjaman::with(['user', 'alat', 'pengembalian'])
            ->where('status', 'selesai')
            ->latest();

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal_peminjaman', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal_peminjaman', '<=', $request->end_date);
        }

        $data = $query->get();

        $filename = "laporan_peminjaman_" . date('Y-m-d_H-i-s') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['ID', 'Peminjam', 'Alat', 'Tgl Pinjam', 'Tgl Kembali', 'Terlambat (Hari)', 'Denda', 'Kondisi Akhir']);

        foreach ($data as $row) {
            $denda = $row->pengembalian ? $row->pengembalian->denda : 0;
            $telat = $row->pengembalian ? $row->pengembalian->keterlambatan_hari : 0;
            $kondisi = $row->pengembalian ? $row->pengembalian->kondisi_alat : '-';

            fputcsv($handle, [
                $row->id,
                $row->user->name,
                $row->alat->nama_alat,
                $row->tanggal_peminjaman,
                $row->tanggal_kembali_aktual,
                $telat,
                $denda,
                $kondisi
            ]);
        }

        fclose($handle);
        exit;
    }
}
