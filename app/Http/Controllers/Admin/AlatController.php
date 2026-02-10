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
        $alats = Alat::with('kategori')->latest()->paginate(10);
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
            'kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'jumlah_total' => ['required', 'integer', 'min:1'],
            'foto' => ['nullable', 'image', 'max:2048'], // 2MB Max
        ]);

        $data = $request->all();
        $data['jumlah_tersedia'] = $request->jumlah_total; // Initially same as total

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('alat', 'public');
            $data['foto'] = $path;
        }

        Alat::create($data);

        return redirect()->route('admin.alat.index')->with('success', 'Alat created successfully.');
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
            'kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'jumlah_total' => ['required', 'integer', 'min:1'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->except(['jumlah_tersedia']); // Don't manually update available quantity easily here, requires logic

        // Adjust jumlah_tersedia if jumlah_total changes
        // Logic: new_available = old_available + (new_total - old_total)
        $diff = $request->jumlah_total - $alat->jumlah_total;
        $data['jumlah_tersedia'] = $alat->jumlah_tersedia + $diff;

        if ($data['jumlah_tersedia'] < 0) {
             return back()->with('error', 'Cannot deduce total below currently borrowed amount.');
        }

        if ($request->hasFile('foto')) {
            if ($alat->foto) {
                Storage::disk('public')->delete($alat->foto);
            }
            $path = $request->file('foto')->store('alat', 'public');
            $data['foto'] = $path;
        }

        $alat->update($data);

        return redirect()->route('admin.alat.index')->with('success', 'Alat updated successfully.');
    }

    public function destroy(Alat $alat)
    {
        if ($alat->peminjaman()->where('status', 'dipinjam')->exists()) {
              return back()->with('error', 'Cannot delete alat that is currently borrowed.');
        }

        if ($alat->foto) {
            Storage::disk('public')->delete($alat->foto);
        }
        
        $alat->delete();
        return redirect()->route('admin.alat.index')->with('success', 'Alat deleted successfully.');
    }
}
