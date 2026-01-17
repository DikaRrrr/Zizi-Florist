<?php

namespace App\Http\Controllers\BackEnd;

use App\Models\AkunBank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AkunBankController extends Controller
{
    public function index()
    {
        $rekening = AkunBank::latest()->get();
        return view('backend.v_rekening.index', compact('rekening'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank'        => 'required|string|max:50',
            'no_rekening' => 'required|numeric|digits_between:5,20',
            'atas_nama'   => 'required|string|max:100',
        ]);

        AkunBank::create([
            'bank'        => strtoupper($request->bank),
            'no_rekening' => $request->no_rekening,
            'atas_nama'   => $request->atas_nama,
            'is_active'   => 1
        ]);

        return back()->with('success', 'Rekening berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $akun = AkunBank::findOrFail($id);
        $akun->delete();

        return back()->with('success', 'Rekening berhasil dihapus');
    }
}
