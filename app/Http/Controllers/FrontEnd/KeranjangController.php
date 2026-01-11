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
        // Nanti disini kita ambil data keranjang dari Database/Session
        return view('frontend.v_keranjang.index');
    }

    // Fungsi Menambahkan ke Keranjang
    public function TambahKeranjang($id)
    {
        $product = Produk::findOrFail($id);

        // Ambil data keranjang yang sudah ada di session (jika belum ada, array kosong)
        $cart = session()->get('cart', []);

        // LOGIKA: Jika produk sudah ada di keranjang, tambah jumlahnya (Quantity)
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Jika belum ada, masukkan data baru
            $cart[$id] = [
                "name" => $product->nama_produk,
                "quantity" => 1,
                "price" => $product->harga,
                "foto" => $product->foto
            ];
        }

        // Simpan kembali ke session
        session()->put('cart', $cart);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    // Fungsi Hapus (Nanti bisa dipakai)
    public function remove($id)
    {
        $cart = session()->get('cart');

        // Cek apakah produk dengan ID tersebut ada di session
        if (isset($cart[$id])) {
            unset($cart[$id]); // Hapus dari array
            session()->put('cart', $cart); // Simpan perubahan ke session
        }

        return redirect()->back();
    }

    public function KeranjangUpdate(Request $request)
    {
        // Pastikan ada ID dan Quantity yang dikirim
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');

            // Update jumlah di array session
            $cart[$request->id]["quantity"] = $request->quantity;

            // Simpan kembali ke session
            session()->put('cart', $cart);
        }
    }
}
