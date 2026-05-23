<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekeningController extends Controller
{
    public function halamanRekening()
    {
        $user = Auth::user();

        return view('freelancer.rekening', compact('user'));
    }

    public function tambahRekening(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'no_akun' => 'required|numeric|digits_between:8,20',
            'nama_akun' => 'required|string|max:100',
        ]);

        Rekening::create([
            'user_id' => Auth::id(),
            'nama_bank' => $request->nama_bank,
            'no_akun' => $request->no_akun,
            'nama_akun' => $request->nama_akun,
        ]);

        return redirect()->route('dashboard.freelance')->with('success', 'Rekening berhasil dibuat');
    }
}
