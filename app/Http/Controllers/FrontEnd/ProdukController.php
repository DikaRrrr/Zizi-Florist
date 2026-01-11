<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function show($id)
    {
        // Cari produk berdasarkan ID, kalau gak ketemu muncul error 404
        $product = Produk::with(['rating.user'])->findOrFail($id);

        $avgRating = $product->rating->avg('rating') ?? 0;

        $totalReviews = $product->rating->count();

        return view('frontend.v_produk.index',compact('product', 'avgRating', 'totalReviews'), [
            'judul' => $product->nama_produk,
            'product' => $product,
        ]);
    }
}
