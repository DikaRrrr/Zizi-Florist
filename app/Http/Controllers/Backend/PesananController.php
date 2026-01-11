<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::orderBy('created_at', 'desc')->get();
        return view('backend.v_pesanan.index', compact('pesanan'));
    }

    public function show($id)
    {
        // Mengambil data pesanan beserta detail produknya dan data user pembeli
        $pesanan = Pesanan::with(['detail.produk', 'user'])->findOrFail($id);

        return view('backend.v_pesanan.show', compact('pesanan'));
    }

    // Tambahkan method update untuk mengubah status
    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $pesanan->detail()->delete();

        $pesanan->delete();

        return redirect()->route('admin.pesanan.index')->with('success', 'Data pesanan berhasil dihapus');
    }
}
