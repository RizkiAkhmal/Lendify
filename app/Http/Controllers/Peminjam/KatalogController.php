<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Alat::with('kategori')->where('kondisi', '!=', 'rusak_berat');

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_alat', 'like', '%' . $request->search . '%')
                  ->orWhere('merk', 'like', '%' . $request->search . '%');
        }

        $alats = $query->paginate(12);
        $kategoris = Kategori::all();

        return view('peminjam.katalog.index', compact('alats', 'kategoris'));
    }

    public function show(Alat $alat)
    {
        return view('peminjam.katalog.show', compact('alat'));
    }
}
