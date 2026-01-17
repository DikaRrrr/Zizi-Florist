<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Produk;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BerandaController extends Controller
{
    public function index()
    {
        $produk = Produk::with('rating')->latest()->paginate(8);
        $best_sellers = Produk::orderBy('terjual', 'desc')->take(5)->get();

        $promo_aktif = Voucher::where('is_active', 1)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->latest()
            ->get();

        $data = [
            'judul' => 'Halaman Utama',
            'produk' => $produk,          
            'best_sellers' => $best_sellers, 
            'promo'=> $promo_aktif, 
        ];

        return view('frontend.v_beranda.index', $data);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $produk = Produk::where('nama_produk', 'LIKE', "%{$keyword}%")
            ->paginate(8); 

        
        $best_sellers = Produk::orderBy('terjual', 'desc')->take(5)->get();

        return view('frontend.v_beranda.index', [
            'produk' => $produk,
            'best_sellers' => $best_sellers,
            'judul' => 'Hasil Pencarian: ' . $keyword
        ]);
    }
}
