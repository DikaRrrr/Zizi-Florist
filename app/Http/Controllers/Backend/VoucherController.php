<?php

namespace App\Http\Controllers\Backend; // Sesuaikan dengan folder kamu

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher; // Pastikan Model Voucher sudah dibuat

class VoucherController extends Controller
{
    // 1. HALAMAN INDEX (DAFTAR VOUCHER)
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();
        return view('backend.v_voucher.index', compact('vouchers'));
    }

    // 2. HALAMAN CREATE (FORM TAMBAH) -> INI YANG MEMPERBAIKI ERROR KAMU
    public function create()
    {
        // Menampilkan file view yang baru saja kamu buat
        return view('backend.v_voucher.create');
    }

    // 3. PROSES SIMPAN DATA (STORE)
    // ... namespace dan use tetap sama ...

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            // unique:nama_tabel,nama_kolom
            'kode_voucher'  => 'required|unique:voucher,kode|max:50',
            'jumlah'        => 'required|numeric',
            'min_belanja'   => 'nullable|numeric',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // 2. Simpan ke Database (Mapping Input -> Kolom DB)
        Voucher::create([
            'kode'              => strtoupper($request->kode_voucher), // Input form 'kode_voucher' masuk ke kolom 'kode'
            'tipe'              => $request->tipe,
            'nilai'             => $request->jumlah,               // Input 'jumlah' masuk ke kolom 'nilai'
            'minimal_pembelian' => $request->min_belanja ?? 0,     // Input 'min_belanja' masuk ke 'minimal_pembelian'
            'tanggal_mulai'     => $request->tanggal_mulai,
            'tanggal_selesai'   => $request->tanggal_akhir,        // Input 'tanggal_akhir' masuk ke 'tanggal_selesai'
            'is_active'         => $request->status == 'aktif' ? 1 : 0, // Ubah 'aktif' jadi 1, 'nonaktif' jadi 0
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil dibuat!');
    }

    public function edit($id)
    {
        // 1. Cari data voucher berdasarkan ID
        $voucher = Voucher::findOrFail($id);

        // 2. Tampilkan halaman edit sambil membawa data voucher
        return view('backend.v_voucher.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi (Gunakan Nama INPUT dari Form HTML: kode_voucher, jumlah)
        $request->validate([
            'kode'    => 'required|max:50|unique:voucher,kode,' . $id, // Form: kode_voucher -> Cek Unik di Kolom: kode
            'nilai'          => 'required|numeric', // Form: jumlah
            'minimal_pembelian'     => 'nullable|numeric', // Form: min_belanja
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai', // Form: tanggal_akhir
        ]);

        $voucher = Voucher::findOrFail($id);

        // 2. Update (Mapping: Kolom DB => $request->NamaInputForm)
        $voucher->update([
            'kode'              => strtoupper($request->kode), // Input 'kode_voucher' masuk ke DB 'kode'
            'tipe'              => $request->tipe,                     // Input 'tipe' (fixed/percent) masuk ke DB 'tipe'
            'nilai'             => $request->nilai,                   // Input 'jumlah' masuk ke DB 'nilai'
            'minimal_pembelian' => $request->minimal_pembelian ?? 0,         // Input 'min_belanja' masuk ke DB
            'tanggal_mulai'     => $request->tanggal_mulai,
            'tanggal_selesai'   => $request->tanggal_selesai,            // Input 'tanggal_akhir' masuk ke DB 'tanggal_selesai'
            'is_active'         => $request->status == 'aktif' ? 1 : 0,
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil diperbarui!');
    }

    // 6. HAPUS DATA (DESTROY)
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil dihapus!');
    }
}
