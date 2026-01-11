<?php

namespace App\Http\Controllers\FrontEnd;

use Carbon\Carbon;
use App\Models\Pesanan;
use App\Models\AkunBank;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    // Menampilkan Halaman Pembayaran
    public function showPayment($id)
    {
        // Cari pesanan berdasarkan ID
        $pesanan = Pesanan::with('detail')->findOrFail($id);
        $banks = AkunBank::where('is_active', 1)->get();

        // Cek keamanan: Pastikan yang akses adalah pemilik pesanan
        if ($pesanan->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Jika status bukan 'menunggu_pembayaran', jangan kasih akses bayar lagi
        if ($pesanan->status != 'Belum Dibayar') {
            return redirect()->route('frontend.beranda')->with('info', 'Pesanan ini sudah diproses.');
        }

        return view('frontend.v_checkout.pembayaran', compact('pesanan', 'banks'));
    }

    // Memproses Upload Bukti Bayar
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'nama_akun_bank' => 'required|string',
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'metode_pembayaran' => 'required|string',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        // 1. Upload File Bukti Bayar
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan di folder 'storage/app/public/bukti_bayar'
            $path = $file->storeAs('bukti_bayar', $filename, 'public');
        }

        // 2. Simpan ke Tabel Pembayaran
        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah' => $pesanan->total_akhir,
            'bukti_bayar' => $filename, // Simpan nama filenya saja
            'nama_akun_bank' => $request->nama_akun_bank,
            'status' => 'diproses', // Status pembayaran
            'waktu_bayar' => Carbon::now(),
        ]);

        // 3. Update Status di Tabel Pesanan
        $pesanan->update([
            'status' => 'Menunggu Konfirmasi'
        ]);

        return redirect()->route('frontend.beranda')->with('success', 'Pembayaran berhasil dikirim! Mohon tunggu konfirmasi dari admin.');
    }
}
