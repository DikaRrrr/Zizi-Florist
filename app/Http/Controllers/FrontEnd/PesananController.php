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
    // MENAMPILKAN DAFTAR PESANAN SAYA
    public function index()
    {
        // Ambil pesanan milik user login
        // 'detail.produk' digunakan untuk mengambil data produk di dalam pesanan (Eager Loading)
        $orders = Pesanan::where('user_id', Auth::id())
            ->with('detail.produk')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.v_pesanan.index', compact('orders'));
    }

    // Function untuk update status jadi 'Selesai' saat tombol 'Terima Pesanan' diklik
    public function markAsReceived($id)
    {
        $order = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $order->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Pesanan telah diterima. Terima kasih!');
    }

    public function cancelOrder($id)
    {
        // Cari pesanan punya user yang sedang login
        $order = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Cek apakah status masih boleh dibatalkan
        if ($order->status == 'Belum Dibayar' || $order->status == 'Menunggu Konfirmasi') {

            $order->update(['status' => 'Dibatalkan']);

            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
    }

    public function submitRating(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'order_id' => 'required|exists:pesanan,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        // 2. Ambil Data Pesanan beserta Detail Produknya
        $pesanan = Pesanan::with('detail')->findOrFail($request->order_id);

        // Cek Keamanan Tambahan: Kalau sudah rated, tolak
        if ($pesanan->sudah_dirating) {
            return response()->json(['success' => false, 'message' => 'Anda sudah menilai pesanan ini.']);
        }

        // Loop simpan rating ke produk (kode lama kamu)
        foreach ($pesanan->detail as $item) {
            Rating::updateOrCreate(
                ['user_id' => Auth::id(), 'produk_id' => $item->produk_id],
                ['rating' => $request->rating, 'komentar' => $request->comment]
            );
        }

        // --- TAMBAHAN PENTING ---
        // Tandai pesanan ini sudah dirating
        $pesanan->update([
            'sudah_dirating' => 1
        ]);

        return response()->json(['success' => true, 'message' => 'Rating berhasil disimpan!']);
    }

    public function checkout()
    {
        $cart = session()->get('cart');

        // 1. Set Nilai Default (Agar tidak error undefined variable saat kosong)
        $subtotal = 0;
        $ongkir = 20000;
        $total_bayar = 0;

        // 2. Jika keranjang ada isinya, hitung subtotal
        if ($cart) {
            foreach ($cart as $id => $details) {
                $subtotal += $details['price'] * $details['quantity'];
            }
            // Hitung Total Bayar
            $total_bayar = $subtotal + $ongkir;
        } else {
            // Opsional: Redirect jika kosong
            // return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        $jumlah_diskon = 0; // Default 0 jika tidak ada voucher

        if (session()->has('voucher')) {
            $sess_voucher = session()->get('voucher');
            // Ambil nilai potongan yang tersimpan di session
            $jumlah_diskon = $sess_voucher['nilai'];
        }

        // 3. Kirim ke View (Gunakan nama variabel yang jelas)
        return view('frontend.v_checkout.index', compact('cart', 'subtotal', 'ongkir', 'total_bayar', 'jumlah_diskon'));
    }

    public function processCheckout(Request $request)
    {
        // ... (Bagian Validasi Tetap Sama) ...

        $cart = session()->get('cart');

        // Hitung Ulang Subtotal (Keamanan Backend)
        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        // Ambil Voucher dari Session (Jika ada)
        $kode_voucher = null;
        $jumlah_diskon = 0;

        if (session()->has('voucher')) {
            $sess = session()->get('voucher');
            $kode_voucher = $sess['kode'];
            $jumlah_diskon = $sess['nilai'];
        }

        $ongkir = 20000;

        // RUMUS FIX: Subtotal + Ongkir - Diskon
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
            session()->forget('voucher'); // Hapus voucher setelah dipakai

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
        $subtotal = $request->subtotal; // Dikirim dari JS

        // 1. Cari Voucher di Database
        $voucher = Voucher::where('kode', $kode)->first();

        // 2. Validasi: Apakah voucher ada?
        if (!$voucher) {
            return response()->json(['status' => 'error', 'message' => 'Kode voucher tidak ditemukan!']);
        }

        // 3. Validasi: Apakah aktif?
        if ($voucher->is_active == 0) {
            return response()->json(['status' => 'error', 'message' => 'Voucher tidak aktif.']);
        }

        // 4. Validasi: Apakah tanggal valid?
        $now = Carbon::now();
        if ($now < $voucher->tanggal_mulai || $now > $voucher->tanggal_selesai) {
            return response()->json(['status' => 'error', 'message' => 'Masa berlaku voucher sudah habis.']);
        }

        // 5. Validasi: Minimal Pembelian
        if ($subtotal < $voucher->minimal_pembelian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Minimal belanja Rp ' . number_format($voucher->minimal_pembelian, 0, ',', '.')
            ]);
        }

        // 6. Hitung Nominal Diskon
        $potongan = 0;
        if ($voucher->tipe == 'persen') {
            $potongan = ($subtotal * $voucher->nilai) / 100;
            // Opsional: Batasi maksimal potongan jika perlu
        } else {
            $potongan = $voucher->nilai;
        }

        $potongan = 0;

        // PERBAIKAN: Gunakan 'percent' sesuai dengan database kamu (bukan 'persen')
        if ($voucher->tipe == 'percent') {
            // Rumus: (Subtotal * Nilai) / 100
            $potongan = ($subtotal * $voucher->nilai) / 100;
        } else {
            // Jika tipe 'fixed', langsung ambil nilainya
            $potongan = $voucher->nilai;
        }

        // Pastikan diskon tidak lebih besar dari total belanja
        if ($potongan > $subtotal) {
            $potongan = $subtotal;
        }

        // Pastikan diskon tidak lebih besar dari subtotal
        if ($potongan > $subtotal) $potongan = $subtotal;

        // 7. Simpan ke Session (PENTING: Agar bisa disimpan ke DB saat checkout)
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

    // MENAMPILKAN DETAIL PESANAN (Invoice)
    public function show($id)
    {
        // Cari pesanan berdasarkan ID, tapi pastikan punya user yang login
        $order = Pesanan::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        return view('frontend.pesanan.show', compact('pesanan'));
    }
}
