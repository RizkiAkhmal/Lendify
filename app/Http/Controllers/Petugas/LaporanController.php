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
            ->whereIn('status', ['selesai', 'rejected'])
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
            ->whereIn('status', ['selesai', 'rejected'])
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

        fputcsv($handle, ['ID Transaksi', 'Peminjam', 'Email', 'Alat', 'Jumlah', 'Tgl Pinjam', 'Tgl Kembali Aktual', 'Keterlambatan (Hari)', 'Denda Keterlambatan', 'Total Denda', 'Kondisi Akhir', 'Catatan Petugas']);

        foreach ($data as $row) {
            $p = $row->pengembalian;
            $dendaTotal = $p ? $p->denda : 0;
            $telat = $p ? $p->keterlambatan_hari : 0;
            $dendaTelat = $telat * 5000;
            $kondisi = $p ? str_replace('_', ' ', $p->kondisi_alat) : 'N/A';
            $tglKembali = ($p && $p->tanggal_kembali_aktual) ? $p->tanggal_kembali_aktual->format('d/m/Y H:i') : '-';

            fputcsv($handle, [
                $row->id,
                strtoupper($row->user->name),
                $row->user->email,
                strtoupper($row->alat->nama_alat),
                $row->jumlah,
                $row->tanggal_pinjam ? $row->tanggal_pinjam->format('d/m/Y H:i') : '-',
                $tglKembali,
                $telat . ' Hari',
                'Rp ' . number_format($dendaTelat, 0, ',', '.'),
                'Rp ' . number_format($dendaTotal, 0, ',', '.'),
                strtoupper($kondisi),
                $p->catatan ?? '-'
            ]);
        }

        fclose($handle);
        exit;
    }
}
