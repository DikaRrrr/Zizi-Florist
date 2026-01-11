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
        // Ambil data terbaru
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

    /**
     * PROSES SIMPAN DATA BARU
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'nama_produk' => 'required|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
            'deskripsi'   => 'required',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'foto.image'           => 'File harus berupa gambar.',
            'foto.max'             => 'Ukuran gambar maksimal 2MB.'
        ]);

        // 2. Buat Slug Otomatis (contoh: "Bunga Mawar Merah" -> "bunga-mawar-merah")
        $validatedData['slug'] = Str::slug($request->nama_produk);

        // 3. Set Terjual default 0 (karena produk baru)
        $validatedData['terjual'] = 0;

        // 4. Proses Upload Gambar (Jika ada)
        if ($request->hasFile('foto')) {
            // Simpan ke folder: storage/app/public/img-produk
            $path = $request->file('foto')->store('img-produk', 'public');
            $validatedData['foto'] = $path;
        }

        // 5. Simpan ke Database
        Produk::create($validatedData);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * MENAMPILKAN DETAIL (Opsional)
     */
    public function show($id)
    {
        // Biasanya jarang dipakai di backend, tapi kalau mau ada:
        $produk = Produk::findOrFail($id);
        return view('backend.v_produk.show', [
            'judul' => 'Detail Produk',
            'data' => $produk
        ]);
    }

    /**
     * MENAMPILKAN FORM EDIT
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('backend.v_produk.edit', [
            'judul' => 'Ubah Produk',
            'edit' => $produk
        ]);
    }

    /**
     * PROSES UPDATE DATA
     */
    public function update(Request $request, $id)
    {
        // Ambil data lama
        $produk = Produk::findOrFail($id);

        // 1. Validasi
        $rules = [
            'nama_produk' => 'required|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
            'deskripsi'   => 'required',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validatedData = $request->validate($rules);

        // 2. Update Slug jika nama berubah
        $validatedData['slug'] = Str::slug($request->nama_produk);

        // 3. Cek apakah user upload gambar baru?
        if ($request->hasFile('foto')) {

            // HAPUS GAMBAR LAMA (Jika ada dan file-nya beneran ada di server)
            if ($produk->foto && Storage::exists('public/' . $produk->foto)) {
                Storage::delete('public/' . $produk->foto);
            }

            // SIMPAN GAMBAR BARU
            $path = $request->file('foto')->store('img-produk', 'public');
            $validatedData['foto'] = $path;
        }

        // 4. Update Database
        $produk->update($validatedData);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * PROSES HAPUS DATA
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // 1. Hapus File Gambar Fisik (Penting biar storage gak penuh)
        if ($produk->foto && Storage::exists('public/' . $produk->foto)) {
            Storage::delete('public/' . $produk->foto);
        }

        // 2. Hapus Data di Database
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
