<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.alat'])->latest()->paginate(15);
        return view('admin.pengembalian.index', compact('pengembalian'));
    }

    public function show(Pengembalian $pengembalian)
    {
        $pengembalian->load(['peminjaman.user', 'peminjaman.alat', 'peminjaman.petugas']);
        return view('admin.pengembalian.show', compact('pengembalian'));
    }

    public function export()
    {
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.alat'])->get();
        $filename = "pengembalian-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://memory', 'w');
        fputcsv($handle, ['ID', 'Peminjam', 'Alat', 'Tgl Pengembalian', 'Kondisi Akhir', 'Keterlambatan (Hari)', 'Denda', 'Catatan']);

        foreach ($pengembalian as $item) {
            fputcsv($handle, [
                $item->id,
                strtoupper($item->peminjaman->user->name ?? '-'),
                strtoupper($item->peminjaman->alat->nama_alat ?? '-'),
                $item->tanggal_kembali_aktual ? $item->tanggal_kembali_aktual->format('d/m/Y H:i') : '-',
                strtoupper(str_replace('_', ' ', $item->kondisi_alat)),
                $item->keterlambatan_hari . ' Hari',
                'Rp ' . number_format($item->denda, 0, ',', '.'),
                $item->catatan
            ]);
        }

        fseek($handle, 0);
        return response()->stream(
            function () use ($handle) {
                fpassthru($handle);
            },
            200,
            [
                "Content-Type" => "text/csv",
                "Content-Disposition" => "attachment; filename=\"$filename\"",
            ]
        );
    }
}
