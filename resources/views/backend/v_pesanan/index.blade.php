@extends('backend.v_layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">

            {{-- Header & Tombol Aksi --}}
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">

                    {{-- BAGIAN KIRI: Breadcrumb --}}
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active" aria-current="page">Pesanan Masuk</li>
                    </ul>

                    {{-- BAGIAN KANAN: Tombol Cetak --}}
                    {{-- Pastikan route ini sudah dibuat di web.php --}}
                    <a href="{{ route('admin.pesanan.formcetak') }}" class="btn btn-success">
                        <i class="ti ti-printer me-2"></i> Cetak Laporan
                    </a>

                </div>
            </div>

            {{-- Tabel Data Pesanan --}}
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="table-pesanan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Invoice ID</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Pastikan di Controller mengirim variabel $pesanan --}}
                                @foreach ($pesanan as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        {{-- Kolom Invoice --}}
                                        <td>
                                            <span class="fw-bold text-primary">#{{ $row->id }}</span>
                                        </td>

                                        {{-- Kolom Nama Pelanggan --}}
                                        <td>
                                            {{-- Mengambil nama user dari relasi (jika ada) --}}
                                            <span class="fw-bold">{{ $row->user->name ?? $row->nama_penerima }}</span>
                                            <br>
                                            <small class="text-muted">{{ $row->no_hp ?? '-' }}</small>
                                        </td>

                                        {{-- Kolom Total Bayar --}}
                                        <td>Rp {{ number_format($row->total_akhir, 0, ',', '.') }}</td>

                                        {{-- Kolom Status (Dengan Logika Warna Badge) --}}
                                        <td>
                                            {{-- 1. Status: Belum Bayar (User baru checkout) --}}
                                            @if ($row->status == 'Belum Dibayar')
                                                <span class="badge bg-secondary">Belum Bayar</span>

                                                {{-- 2. Status: Menunggu Konfirmasi (User sudah upload bukti, Admin perlu cek) --}}
                                            @elseif ($row->status == 'Menunggu Konfirmasi')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="ti ti-clock"></i> Cek Bukti Bayar
                                                </span>

                                                {{-- 3. Status: Diproses/Dibayar (Admin sudah terima uang, sedang packing) --}}
                                            @elseif ($row->status == 'Diproses' || $row->status == 'Dibayar')
                                                <span class="badge bg-primary">Dikemas / Lunas</span>

                                                {{-- 4. Status: Dikirim (Barang di kurir) --}}
                                            @elseif ($row->status == 'Dikirim')
                                                <span class="badge bg-info text-dark">
                                                    <i class="ti ti-truck"></i> Sedang Dikirim
                                                </span>

                                                {{-- 5. Status: Selesai (Barang sampai) --}}
                                            @elseif ($row->status == 'Selesai')
                                                <span class="badge bg-success">
                                                    <i class="ti ti-circle-check"></i> Selesai
                                                </span>

                                                {{-- 6. Status: Pembayaran Ditolak (Bukti palsu/buram) --}}
                                            @elseif ($row->status == 'Pembayaran Ditolak')
                                                <span class="badge bg-dark text-white">
                                                    <i class="ti ti-ban"></i> Pembayaran Ditolak
                                                </span>

                                                {{-- 7. Status: Dibatalkan (Order hangus) --}}
                                            @elseif ($row->status == 'Dibatalkan')
                                                <span class="badge bg-danger">Dibatalkan</span>

                                                {{-- Default: Jika ada status lain --}}
                                            @else
                                                <span class="badge bg-light text-dark border">{{ $row->status }}</span>
                                            @endif
                                        </td>

                                        {{-- Kolom Tanggal --}}
                                        <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>

                                        {{-- Kolom Aksi --}}
                                        <td>
                                            {{-- Tombol Detail (Lihat isi pesanan bunga apa saja) --}}
                                            {{-- Asumsi route: admin.pesanan.show --}}
                                            <a href="{{ route('admin.pesanan.show', $row->id) }}"
                                                class="btn btn-sm btn-primary" title="Lihat Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <form method="POST" action="{{ route('admin.pesanan.destroy', $row->id) }}"
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
