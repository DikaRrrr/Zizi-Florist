<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BerandaController extends Controller
{
    public function index()
    {
        // 1. Hitung Total Produk
        $totalProduk = Produk::count();

        // 2. Hitung User (Hanya role customer/user)
        $totalUser = User::where('role', 'user')->count(); // Sesuaikan 'user' atau 'customer' sesuai database kamu

        // 3. Hitung Pendapatan (Hanya status yang uangnya sudah cair)
        // Kita gunakan whereIn untuk memilih banyak status sekaligus
        $totalPendapatan = Pesanan::whereIn('status', ['Dibayar', 'Diproses', 'Dikirim', 'Selesai'])
            ->sum('total_akhir');

        // 4. Hitung Total Pesanan Masuk (Semua status) - Opsional, buat statistik
        $totalPesanan = Pesanan::count();

        // 5. Hitung Pesanan Perlu Diproses (Pending)
        $perluDiproses = Pesanan::where('status', 'Menunggu Konfirmasi')->count();

        // Kirim semua variabel ke View
        return view('backend.v_beranda.index', compact(
            'totalProduk',
            'totalUser',
            'totalPendapatan',
            'totalPesanan',
            'perluDiproses'
        ));
    }
}
