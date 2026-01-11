@extends('backend.v_layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">

            {{-- Header & Tombol Tambah --}}
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Produk</li>
                    </ul> <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Tambah Produk
                    </a>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="table-produk">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Terjual</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($index as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        {{-- Kolom Foto --}}
                                        <td>
                                            @if ($row->foto)
                                                {{-- Pastikan path storage benar --}}
                                                <img src="{{ asset('storage/' . $row->foto) }}" width="60"
                                                    height="60" class="rounded object-fit-cover" alt="Foto Produk">
                                            @else
                                                {{-- Gambar Placeholder jika tidak ada foto --}}
                                                <img src="https://placehold.co/60x60?text=No+Img" width="60"
                                                    height="60" class="rounded">
                                            @endif
                                        </td>

                                        {{-- Kolom Nama Produk --}}
                                        <td>
                                            <span class="fw-bold">{{ $row->nama_produk }}</span>
                                            <br>
                                            <small class="text-muted">Slug: {{ $row->slug }}</small>
                                        </td>

                                        {{-- Kolom Harga (Format Rupiah) --}}
                                        <td>Rp {{ number_format($row->harga, 0, ',', '.') }}</td>

                                        {{-- Kolom Stok --}}
                                        <td>
                                            @if ($row->stok <= 5)
                                                <span class="badge bg-danger">{{ $row->stok }} (Menipis)</span>
                                            @else
                                                <span class="badge bg-success">{{ $row->stok }}</span>
                                            @endif
                                        </td>

                                        {{-- Kolom Terjual --}}
                                        <td>{{ $row->terjual }}</td>

                                        {{-- Kolom Aksi --}}
                                        <td>
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.produk.edit', $row->id) }}"
                                                class="btn btn-sm btn-info text-white" title="Ubah Data">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <form method="POST" action="{{ route('admin.produk.destroy', $row->id) }}"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                                    title="Hapus Data">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
