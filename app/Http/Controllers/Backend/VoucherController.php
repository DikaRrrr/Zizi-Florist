<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

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
        return view('backend.v_voucher.create');
    }

    // 3. PROSES SIMPAN DATA (STORE)

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'kode'  => 'required|unique:voucher,kode|max:50',
            'nilai' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipe == 'percent' && $value > 100) {
                        $fail('Nilai persentase tidak boleh lebih dari 100%.');
                    }
                },
            ],
            'tipe' => 'required|in:fixed,percent',
            'minimal_pembelian' => 'nullable|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // 2. Simpan ke Database (Mapping Input -> Kolom DB)
        Voucher::create([
            'kode'              => strtoupper($request->kode_voucher),
            'tipe'              => $request->tipe,
            'nilai'             => $request->nilai,               
            'minimal_pembelian' => $request->min_belanja ?? 0,     
            'tanggal_mulai'     => $request->tanggal_mulai,
            'tanggal_selesai'   => $request->tanggal_akhir,        
            'is_active'         => $request->status == 'aktif' ? 1 : 0,
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil dibuat!');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);

        return view('backend.v_voucher.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode'    => 'required|max:50|unique:voucher,kode,' . $id,
            'nilai' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipe == 'percent' && $value > 100) {
                        $fail('Nilai persentase tidak boleh lebih dari 100%.');
                    }
                },
            ],
            'minimal_pembelian' => 'nullable|numeric|min:0',
            'tipe' => 'required|in:fixed,percent',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $voucher = Voucher::findOrFail($id);

        $voucher->update([
            'kode'              => strtoupper($request->kode), 
            'tipe'              => $request->tipe,                     
            'nilai'             => $request->nilai,                   
            'minimal_pembelian' => $request->minimal_pembelian ?? 0,         
            'tanggal_mulai'     => $request->tanggal_mulai,
            'tanggal_selesai'   => $request->tanggal_selesai,            
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

    public function formCetak()
    {
        $judul = "Form Cetak Laporan Voucher";
        return view('backend.v_voucher.form', compact('judul'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal  = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $cetak = Voucher::whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->latest()
            ->get();

        $judul = "Laporan Data Voucher";

        return view('backend.v_voucher.cetak', compact('cetak', 'judul', 'tanggalAwal', 'tanggalAkhir'));
    }
}
