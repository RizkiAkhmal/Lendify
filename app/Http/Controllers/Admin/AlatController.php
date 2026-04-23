<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function index()
    {
        $alats = Alat::with('kategori')->oldest()->paginate(10);
        return view('admin.alat.index', compact('alats'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.alat.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => ['required', 'exists:kategori,id'],
            'kode_alat' => ['required', 'string', 'max:50', 'unique:alat'],
            'nama_alat' => ['required', 'string', 'max:255'],
            'merk' => ['nullable', 'string', 'max:100'],
            'spesifikasi' => ['nullable', 'string'],
            'jumlah_total' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'], // 2MB Max
        ]);

        $data = $request->all();
        $data['jumlah_rusak'] = 0;
        // Initial available = total
        $data['jumlah_tersedia'] = $request->jumlah_total;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('alat', 'public');
            $data['foto'] = $path;
        }

        Alat::create($data);

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function show(Alat $alat)
    {
        return view('admin.alat.show', compact('alat'));
    }

    public function edit(Alat $alat)
    {
        $kategori = Kategori::all();
        return view('admin.alat.edit', compact('alat', 'kategori'));
    }

    public function update(Request $request, Alat $alat)
    {
        $request->validate([
            'kategori_id' => ['required', 'exists:kategori,id'],
            'kode_alat' => ['required', 'string', 'max:50', 'unique:alat,kode_alat,' . $alat->id],
            'nama_alat' => ['required', 'string', 'max:255'],
            'merk' => ['nullable', 'string', 'max:100'],
            'spesifikasi' => ['nullable', 'string'],
            'jumlah_total' => ['required', 'integer', 'min:0'],
            'jumlah_rusak' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->all();

        // Calculate changes
        $diffTotal = $request->jumlah_total - $alat->jumlah_total;
        $diffRusak = $request->jumlah_rusak - $alat->jumlah_rusak;

        // Formula: New Available = Old Available + (Change in Total) - (Change in Broken)
        $data['jumlah_tersedia'] = $alat->jumlah_tersedia + $diffTotal - $diffRusak;

        if ($data['jumlah_tersedia'] < 0) {
             return back()->withInput()->withErrors(['jumlah_rusak' => 'Penyesuaian gagal: Stok tersedia tidak mencukupi (melebihi unit yang sedang dipinjam).']);
        }

        if ($request->hasFile('foto')) {
            if ($alat->foto) {
                Storage::disk('public')->delete($alat->foto);
            }
            $path = $request->file('foto')->store('alat', 'public');
            $data['foto'] = $path;
        }

        $alat->update($data);

        return redirect()->route('admin.alat.index')->with('success', 'Alat Berhasil Diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        // 1. Check if tool is currently being borrowed
        if ($alat->peminjaman()->whereIn('status', ['approved', 'dipinjam'])->exists()) {
              return back()->with('error', 'Alat tidak dapat dihapus karena sedang dalam proses peminjaman aktif.');
        }

        // 2. Check for historical records that prevent deletion due to FK Restrict
        if ($alat->peminjaman()->exists()) {
            return back()->with('error', 'Alat tidak dapat dihapus karena memiliki riwayat transaksi di masa lalu. Anda dapat mengubah statusnya menjadi tidak aktif jika diperlukan.');
        }

        if ($alat->foto) {
            Storage::disk('public')->delete($alat->foto);
        }
        
        $alat->delete();
        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil dihapus');
    }

    public function export()
    {
        $alats = Alat::with('kategori')->get();
        $filename = "alat-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://memory', 'w');
        fputcsv($handle, ['ID', 'Kode Alat', 'Nama Alat', 'Kategori', 'Merk', 'Stok Total', 'Stok Tersedia', 'Stok Rusak']);

        foreach ($alats as $alat) {
            fputcsv($handle, [
                $alat->id,
                $alat->kode_alat,
                $alat->nama_alat,
                $alat->kategori->nama_kategori ?? '-',
                $alat->merk,
                $alat->jumlah_total,
                $alat->jumlah_tersedia,
                $alat->jumlah_rusak
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
    public function showRepair(Alat $alat)
    {
        return view('admin.alat.repair', compact('alat'));
    }

    public function postRepair(Request $request, Alat $alat)
    {
        $request->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:' . $alat->jumlah_rusak],
        ]);

        $alat->decrement('jumlah_rusak', $request->jumlah);
        $alat->increment('jumlah_tersedia', $request->jumlah);

        \App\Models\LogAktivitas::catat(auth()->id(), 'REPAIR', 'alat', null, [
            'nama_alat' => $alat->nama_alat,
            'jumlah_diperbaiki' => $request->jumlah,
            'sisa_rusak' => $alat->jumlah_rusak,
            'stok_tersedia_baru' => $alat->jumlah_tersedia
        ]);

        return redirect()->route('admin.alat.index')->with('success', $request->jumlah . ' unit berhasil diperbaiki dan dikembalikan ke stok tersedia.');
    }
}
