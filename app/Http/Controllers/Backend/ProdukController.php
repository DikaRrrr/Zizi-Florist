<?php

namespace App\Http\Controllers\Backend;

use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::latest()->get();

        return view('backend.v_produk.index', [
            'judul' => 'Data Produk',
            'index' => $produk
        ]);
    }

    public function create()
    {
        return view('backend.v_produk.create', [
            'judul' => 'Tambah Produk Baru'
        ]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_produk' => 'required|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'deskripsi'   => 'required',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'foto.image'           => 'File harus berupa gambar.',
            'foto.max'             => 'Ukuran gambar maksimal 2MB.'
        ]);

        $validatedData['slug'] = Str::slug($request->nama_produk);

        $validatedData['terjual'] = 0;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('img-produk', 'public');
            $validatedData['foto'] = $path;
        }

        Produk::create($validatedData);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }


    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('backend.v_produk.show', [
            'judul' => 'Detail Produk',
            'data' => $produk
        ]);
    }


    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('backend.v_produk.edit', [
            'judul' => 'Ubah Produk',
            'edit' => $produk
        ]);
    }


    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $rules = [
            'nama_produk' => 'required|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
            'deskripsi'   => 'required',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validatedData = $request->validate($rules);

        $validatedData['slug'] = Str::slug($request->nama_produk);

        if ($request->hasFile('foto')) {

            if ($produk->foto && Storage::exists('public/' . $produk->foto)) {
                Storage::delete('public/' . $produk->foto);
            }

            $path = $request->file('foto')->store('img-produk', 'public');
            $validatedData['foto'] = $path;
        }

        $produk->update($validatedData);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->foto && Storage::exists('public/' . $produk->foto)) {
            Storage::delete('public/' . $produk->foto);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function formcetak()
    {
        $judul = "Form Cetak Laporan Produk";
        return view('backend.v_produk.form', compact('judul'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal  = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $cetak = Produk::whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->get();

        $judul = "Laporan Data Produk";

        return view('backend.v_produk.cetak', compact('cetak', 'judul', 'tanggalAwal', 'tanggalAkhir'));
    }
}
