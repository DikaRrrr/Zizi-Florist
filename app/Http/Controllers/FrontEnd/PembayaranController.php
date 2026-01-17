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
    public function showPayment($id)
    {
        $pesanan = Pesanan::with('detail')->findOrFail($id);
        $banks = AkunBank::where('is_active', 1)->get();

        if ($pesanan->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($pesanan->status != 'Belum Dibayar') {
            return redirect()->route('frontend.beranda')->with('info', 'Pesanan ini sudah diproses.');
        }

        return view('frontend.v_checkout.pembayaran', compact('pesanan', 'banks'));
    }

    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'nama_akun_bank' => 'required|string',
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'metode_pembayaran' => 'required|string',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bukti_bayar', $filename, 'public');
        }

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah' => $pesanan->total_akhir,
            'bukti_bayar' => $filename,
            'nama_akun_bank' => $request->nama_akun_bank,
            'status' => 'diproses',
            'waktu_bayar' => Carbon::now(),
        ]);

        $pesanan->update([
            'status' => 'Menunggu Konfirmasi'
        ]);

        return redirect()->route('frontend.beranda')->with('success', 'Pembayaran berhasil dikirim! Mohon tunggu konfirmasi dari admin.');
    }
}
