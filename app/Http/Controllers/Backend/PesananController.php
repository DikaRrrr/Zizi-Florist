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
        $pesanan = Pesanan::with(['detail.produk', 'user'])->findOrFail($id);

        return view('backend.v_pesanan.show', compact('pesanan'));
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'status' => $request->status,
            'resi'   => $request->resi,
        ]);

        if ($pesanan->pembayaran) {

            if (in_array($request->status, ['Diproses', 'Dikirim', 'Selesai'])) {
                $pesanan->pembayaran->update([
                    'status' => 'berhasil'
                ]);
            }
            elseif ($request->status == 'Pembayaran Ditolak' || $request->status == 'Dibatalkan') {
                $pesanan->pembayaran->update([
                    'status' => 'gagal'
                ]);
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $pesanan->detail()->delete();

        $pesanan->delete();

        return redirect()->route('admin.pesanan.index')->with('success', 'Data pesanan berhasil dihapus');
    }

    public function formCetak()
    {
        $judul = "Form Cetak Laporan Pesanan";
        return view('backend.v_pesanan.form', compact('judul'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal  = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $cetak = Pesanan::with('user')
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->get();

        $judul = "Laporan Riwayat Pesanan";

        return view('backend.v_pesanan.cetak', compact('cetak', 'judul', 'tanggalAwal', 'tanggalAkhir'));
    }
}
