@extends('backend.v_layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">

            {{-- 1. HEADER & BREADCRUMB (Sesuai Template Anda) --}}
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.pesanan.index') }}">Pesanan Masuk</a></li>
                        <li class="breadcrumb-item" aria-current="page">Detail Order #{{ $pesanan->id }}</li>
                    </ul>

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                {{-- KOLOM KIRI: Daftar Barang yang Dibeli --}}
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Daftar Item Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- Menggunakan class table yang sama dengan template Anda --}}
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pesanan->detail as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        {{-- Cek Foto --}}
                                                        @if ($item->produk && $item->produk->foto)
                                                            <img src="{{ asset('storage/' . $item->produk->foto) }}"
                                                                width="40" class="rounded me-2" alt="Foto">
                                                        @endif
                                                        <span
                                                            class="fw-bold">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</span>
                                                    </div>
                                                </td>
                                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                <td>{{ $item->jumlah }}</td>
                                                <td class="text-end">
                                                    Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        {{-- Rincian Biaya --}}
                                        <tr>
                                            <td colspan="4" class="text-end">Subtotal</td>
                                            <td class="text-end fw-bold">Rp
                                                {{ number_format($pesanan->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">Ongkos Kirim</td>
                                            <td class="text-end">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</td>
                                        </tr>
                                        @if ($pesanan->jumlah_diskon > 0)
                                            <tr>
                                                <td colspan="4" class="text-end text-success">Diskon
                                                    ({{ $pesanan->kode_voucher }})</td>
                                                <td class="text-end text-success">- Rp
                                                    {{ number_format($pesanan->jumlah_diskon, 0, ',', '.') }}</td>
                                            </tr>
                                        @endif
                                        <tr class="table-primary">
                                            <td colspan="4" class="text-end fw-bold fs-5">TOTAL BAYAR</td>
                                            <td class="text-end fw-bold fs-5">Rp
                                                {{ number_format($pesanan->total_akhir, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Form Update Status & Info Pembeli --}}
                <div class="col-lg-4">

                    {{-- Card Bukti Pembayaran (Ambil dari Tabel Pembayaran) --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Bukti Pembayaran</h5>
                        </div>
                        <div class="card-body text-center">

                            {{-- 1. Cek apakah ada data di tabel pembayaran --}}
                            {{-- 2. Cek apakah kolom bukti_bayar ada isinya --}}
                            @if ($pesanan->pembayaran && $pesanan->pembayaran->bukti_bayar)
                                {{-- Tampilkan Gambar --}}
                                {{-- Sesuaikan 'bukti_bayar' dengan nama kolom di tabel pembayaran kamu --}}
                                <img src="{{ asset('storage/bukti_bayar/' . $pesanan->pembayaran->bukti_bayar) }}"
                                    class="img-fluid rounded mb-2" alt="Bukti Bayar"
                                    style="max-height: 200px; object-fit: contain;">

                                <br>

                                <a href="{{ asset('storage/bukti_bayar/' . $pesanan->pembayaran->bukti_bayar) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="ti ti-zoom-in"></i> Lihat Ukuran Penuh
                                </a>

                                <hr>
                                {{-- Info Tambahan dari Tabel Pembayaran (Opsional) --}}
                                <small class="text-muted d-block">
                                    Nama Pengirim: <b>{{ $pesanan->pembayaran->nama_akun_bank ?? '-' }}</b><br>
                                    Bank: <b>{{ $pesanan->pembayaran->metode_pembayaran ?? '-' }}</b><br>
                                    Tanggal: {{ $pesanan->pembayaran->created_at->format('d M Y') }}
                                </small>
                            @else
                                {{-- Jika belum ada pembayaran --}}
                                <div class="alert alert-warning mb-0 text-start">
                                    <i class="ti ti-alert-circle"></i> Belum ada data pembayaran masuk.
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- CARD 1: UPDATE STATUS --}}
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 text-white">Aksi Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Update Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Belum Dibayar"
                                            {{ $pesanan->status == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar
                                        </option>
                                        <option value="Menunggu Konfirmasi"
                                            {{ $pesanan->status == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu
                                            Konfirmasi</option>
                                        <option value="Diproses" {{ $pesanan->status == 'Diproses' ? 'selected' : '' }}>
                                            Diproses</option>
                                        <option value="Dibayar" {{ $pesanan->status == 'Dibayar' ? 'selected' : '' }}>
                                            Dibayar</option>
                                        <option value="Dikirim" {{ $pesanan->status == 'Dikirim' ? 'selected' : '' }}>
                                            Dikirim</option>
                                        <option value="Selesai" {{ $pesanan->status == 'Selesai' ? 'selected' : '' }}>
                                            Selesai</option>
                                        <option value="Dibatalkan"
                                            {{ $pesanan->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        <option value="Pembayaran Ditolak"
                                            {{ $pesanan->status == 'Pembayaran Ditolak' ? 'selected' : '' }}>Pembayaran
                                            Ditolak (Minta Upload Ulang)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Input Resi (Jika Dikirim)</label>
                                    <input type="text" name="resi" class="form-control" value="{{ $pesanan->resi }}"
                                        placeholder="Contoh: JNE123456">
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-device-floppy"></i> Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- CARD 2: INFO PENERIMA --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Data Penerima</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="p-0 pb-2 text-muted" width="30%">Nama</td>
                                    <td class="p-0 pb-2 fw-bold">{{ $pesanan->nama_penerima }}</td>
                                </tr>
                                <tr>
                                    <td class="p-0 pb-2 text-muted">No HP</td>
                                    <td class="p-0 pb-2 fw-bold">{{ $pesanan->hp_penerima }}</td>
                                </tr>
                                <tr>
                                    <td class="p-0 pb-2 text-muted">Alamat</td>
                                    <td class="p-0 pb-2">{{ $pesanan->alamat_penerima }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
