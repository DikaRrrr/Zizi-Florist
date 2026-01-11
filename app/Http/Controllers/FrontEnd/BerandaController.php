<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BerandaController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Produk (Paginasi 8 per halaman)
        $produk = Produk::latest()->paginate(8);

        // 2. Ambil Best Seller (5 Terbanyak)
        $best_sellers = Produk::orderBy('terjual', 'desc')->take(5)->get();

        // 3. Kirim ke View (Jadikan satu array agar rapi)
        $data = [
            'judul' => 'Halaman Utama',
            'produk' => $produk,           // Ini variabel untuk looping produk utama
            'best_sellers' => $best_sellers // Ini variabel untuk slider best seller
        ];

        return view('frontend.v_beranda.index', $data);
    }

    public function search(Request $request)
    {
        // 1. Ambil kata kunci dari input
        $keyword = $request->input('keyword');

        // 2. Query ke Database (Cari berdasarkan nama produk)
        $produk = Produk::where('nama_produk', 'LIKE', "%{$keyword}%")
            ->paginate(8); // Tetap pakai pagination

        // 3. (Opsional) Ambil Best Seller juga agar tidak error di view
        // Karena view kamu me-looping $best_sellers, variabel ini HARUS tetap dikirim
        $best_sellers = Produk::orderBy('terjual', 'desc')->take(5)->get();

        // 4. Kirim ke View (Kita gunakan view yang sama dengan beranda atau buat baru)
        return view('frontend.v_beranda.index', [
            'produk' => $produk,
            'best_sellers' => $best_sellers,
            'judul' => 'Hasil Pencarian: ' . $keyword
        ]);
    }
}
