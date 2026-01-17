<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller
{
    public function index()
    {
        $rating = Rating::with(['user', 'produk'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.v_rating.index', [
            'judul' => 'Riwayat Rating & Ulasan',
            'rating' => $rating
        ]);
    }


    public function formCetak()
    {
        $judul = "Form Cetak Laporan Rating";
        return view('backend.v_rating.form', compact('judul'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal  = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $cetak = Rating::with(['user', 'produk'])
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->orderBy('created_at', 'DESC')
            ->get();

        $judul = "Laporan Riwayat Ulasan Pelanggan";

        return view('backend.v_rating.cetak', compact('cetak', 'judul', 'tanggalAwal', 'tanggalAkhir'));
    }
}
