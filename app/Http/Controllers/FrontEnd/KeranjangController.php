<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Produk;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        return view('frontend.v_keranjang.index');
    }

    public function TambahKeranjang($id)
    {
        $product = Produk::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->nama_produk,
                "quantity" => 1,
                "price" => $product->harga,
                "foto" => $product->foto
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function KeranjangUpdate(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');

            $cart[$request->id]["quantity"] = $request->quantity;

            session()->put('cart', $cart);
        }
    }
}
