<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_alat' => Alat::count(),
            'total_pinjaman' => $user->role === 'peminjam' 
                ? Peminjaman::where('user_id', $user->id)->count()
                : 0,
            'pending' => $user->role === 'peminjam' 
                ? Peminjaman::where('user_id', $user->id)->where('status', 'pending')->count()
                : Peminjaman::where('status', 'pending')->count(),
            'selesai' => $user->role === 'peminjam' 
                ? Peminjaman::where('user_id', $user->id)->where('status', 'selesai')->count()
                : Peminjaman::where('status', 'selesai')->count(),
            'rusak' => Alat::sum('jumlah_rusak'),
            'total_denda' => \App\Models\Pengembalian::sum('denda'),
        ];

        // Data khusus per role
        $latestActivities = collect();
        $alatTersedia = collect();
        $recentLoans = collect();
        $recentAlats = collect();

        if ($user->role === 'peminjam') {
            // Peminjam: ambil alat tersedia
            $alatTersedia = Alat::with('kategori')
                ->tersedia()
                ->latest()
                ->take(8)
                ->get();
        } else {
            // Admin & Petugas: ambil log aktivitas dan data terbaru
            $latestActivities = LogAktivitas::with('user')->latest()->take(5)->get();
            $recentLoans = Peminjaman::with('user', 'alat')->latest()->take(5)->get();
            $recentAlats = Alat::with('kategori')->latest()->take(5)->get();
        }

        return view('dashboard', compact('user', 'stats', 'latestActivities', 'alatTersedia', 'recentLoans', 'recentAlats'));
    }
}
