<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('dashboard', ['role' => 'admin']);
        } elseif ($user->role === 'petugas') {
            return view('dashboard', ['role' => 'petugas']);
        } elseif ($user->role === 'peminjam') {
            return view('dashboard', ['role' => 'peminjam']);
        }

        return view('dashboard');
    }
}
