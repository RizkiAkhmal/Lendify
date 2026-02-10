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
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'selesai' => Peminjaman::where('status', 'selesai')->count(),
            'rusak' => Alat::where('kondisi', 'rusak_berat')->count(),
        ];

        $latestActivities = LogAktivitas::with('user')->latest()->take(5)->get();
        
        // Jika log kosong, ambil peminjaman terbaru sebagai fallback
        if ($latestActivities->isEmpty()) {
            $latestActivities = Peminjaman::with('user', 'alat')->latest()->take(5)->get();
        }

        return view('dashboard', compact('user', 'stats', 'latestActivities'));
    }
}
