<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\Rating;
use App\Models\Pesanan;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index()
    {
        $orders = Pesanan::where('user_id', Auth::id())
            ->with('detail.produk')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.v_pesanan.index', compact('orders'));
    }

    public function markAsReceived($id)
    {
        $order = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $order->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Pesanan telah diterima. Terima kasih!');
    }

    public function cancelOrder($id)
    {
        $order = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status == 'Belum Dibayar' || $order->status == 'Menunggu Konfirmasi') {

            $order->update(['status' => 'Dibatalkan']);

            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
    }

    public function submitRating(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:pesanan,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $pesanan = Pesanan::with('detail')->findOrFail($request->order_id);

        if ($pesanan->sudah_dirating) {
            return response()->json(['success' => false, 'message' => 'Anda sudah menilai pesanan ini.']);
        }

        foreach ($pesanan->detail as $item) {
            Rating::updateOrCreate(
                ['user_id' => Auth::id(), 'produk_id' => $item->produk_id],
                ['rating' => $request->rating, 'komentar' => $request->comment]
            );
        }

        $pesanan->update([
            'sudah_dirating' => 1
        ]);

        return response()->json(['success' => true, 'message' => 'Rating berhasil disimpan!']);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart');

        $subtotal = 0;
        $ongkir = 20000;
        $total_bayar = 0;

        if ($cart) {
            foreach ($cart as $id => $details) {
                $subtotal += $details['price'] * $details['quantity'];
            }
            $total_bayar = $subtotal + $ongkir;
        } else {
        }

        $jumlah_diskon = 0;

        if (session()->has('voucher')) {
            $sess_voucher = session()->get('voucher');
            $jumlah_diskon = $sess_voucher['nilai'];
        }

        return view('frontend.v_checkout.index', compact('cart', 'subtotal', 'ongkir', 'total_bayar', 'jumlah_diskon'));
    }

    public function processCheckout(Request $request)
    {

        $cart = session()->get('cart');

        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $kode_voucher = null;
        $jumlah_diskon = 0;

        if (session()->has('voucher')) {
            $sess = session()->get('voucher');
            $kode_voucher = $sess['kode'];
            $jumlah_diskon = $sess['nilai'];
        }

        $ongkir = 20000;

        $total_akhir = ($subtotal + $ongkir) - $jumlah_diskon;

        try {
            DB::beginTransaction();

            $pesanan = Pesanan::create([
                'user_id' => Auth::id(),
                'nama_penerima' => $request->nama_penerima,
                'hp_penerima' => $request->hp_penerima,
                'alamat_penerima' => $request->alamat_penerima,
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'kode_voucher' => $kode_voucher,
                'jumlah_diskon' => $jumlah_diskon,
                'total_akhir' => $total_akhir,
                'status' => 'Belum Dibayar',
                'resi' => null,
            ]);

            foreach ($cart as $id_produk => $details) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $id_produk,
                    'quantity' => $details['quantity'],
                    'harga' => $details['price'],
                ]);

                $produk = \App\Models\Produk::find($id_produk);
                if ($produk) {
                    $produk->increment('terjual', $details['quantity']);
                    $produk->decrement('stok', $details['quantity']);
                }
            }

            session()->forget('cart');
            session()->forget('voucher');

            DB::commit();
            return redirect()->route('payment.show', $pesanan->id);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function applyVoucher(Request $request)
    {
        $kode = $request->kode_voucher;
        $subtotal = $request->subtotal; 

        $voucher = Voucher::where('kode', $kode)->first();

        if (!$voucher) {
            return response()->json(['status' => 'error', 'message' => 'Kode voucher tidak ditemukan!']);
        }

        if ($voucher->is_active == 0) {
            return response()->json(['status' => 'error', 'message' => 'Voucher tidak aktif.']);
        }

        $now = Carbon::now();
        if ($now < $voucher->tanggal_mulai || $now > $voucher->tanggal_selesai) {
            return response()->json(['status' => 'error', 'message' => 'Masa berlaku voucher sudah habis.']);
        }

        if ($subtotal < $voucher->minimal_pembelian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Minimal belanja Rp ' . number_format($voucher->minimal_pembelian, 0, ',', '.')
            ]);
        }

        $potongan = 0;
        if ($voucher->tipe == 'persen') {
            $potongan = ($subtotal * $voucher->nilai) / 100;
        } else {
            $potongan = $voucher->nilai;
        }

        $potongan = 0;

        if ($voucher->tipe == 'percent') {
            $potongan = ($subtotal * $voucher->nilai) / 100;
        } else {
            $potongan = $voucher->nilai;
        }

        if ($potongan > $subtotal) {
            $potongan = $subtotal;
        }

        if ($potongan > $subtotal) $potongan = $subtotal;

        session()->put('voucher', [
            'kode' => $voucher->kode,
            'nilai' => $potongan
        ]);

        return response()->json([
            'status' => 'success',
            'nilai_potongan' => $potongan,
            'message' => 'Voucher berhasil digunakan!'
        ]);
    }

    public function show($id)
    {
        $order = Pesanan::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        return view('frontend.pesanan.show', compact('pesanan'));
    }
}
