<?php

namespace App\Http\Controllers\BackEnd;

use App\Models\AkunBank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AkunBankController extends Controller
{
    public function index()
    {
        // Ambil data rekening
        $rekening = AkunBank::latest()->get();
        return view('backend.v_rekening.index', compact('rekening'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Sesuai name di Form View)
        $request->validate([
            'bank'        => 'required|string|max:50',
            'no_rekening' => 'required|numeric',
            'atas_nama'   => 'required|string|max:100',
        ]);

        // 2. Simpan ke Database (Mapping otomatis karena name form = nama kolom)
        AkunBank::create([
            'bank'        => strtoupper($request->bank), // Paksa huruf besar (BCA, BRI)
            'no_rekening' => $request->no_rekening,
            'atas_nama'   => $request->atas_nama,
            'is_active'   => 1 // Default langsung Aktif saat dibuat
        ]);

        return back()->with('success', 'Rekening berhasil ditambahkan');
    }

    public function destroy($id)
    {
        // Hapus data
        $akun = AkunBank::findOrFail($id);
        $akun->delete();

        return back()->with('success', 'Rekening berhasil dihapus');
    }
}
