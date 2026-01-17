@extends('frontend.v_layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="mb-4 text-center">Selesaikan Pembayaran</h2>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Instruksi Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <p>Silakan transfer total tagihan ke salah satu rekening di bawah ini:</p>

                                <div class="alert alert-warning text-center">
                                    <small>Total yang harus dibayar:</small>
                                    <h3 class="fw-bold">Rp {{ number_format($pesanan->total_akhir, 0, ',', '.') }}</h3>
                                    <small class="text-danger">ID Pesanan: #{{ $pesanan->id }}</small>
                                </div>

                                <label class="form-label fw-bold">Daftar Rekening Tujuan:</label>
                                <ul class="list-group mb-3">
                                    @forelse($banks as $item)
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    {{-- Nama Bank & No Rekening --}}
                                                    <strong class="text-uppercase text-primary">{{ $item->bank }}</strong>
                                                    <span class="fw-bold text-dark">- {{ $item->no_rekening }}</span>
                                                    <br>
                                                    {{-- Atas Nama --}}
                                                    <small class="text-muted" style="text-transform: uppercase">a.n. {{ $item->atas_nama }}</small>
                                                </div>

                                                {{-- (Opsional) Tombol Copy kecil agar user mudah menyalin --}}
                                                <button type="button" class="btn btn-sm btn-light border"
                                                    onclick="navigator.clipboard.writeText('{{ $item->no_rekening }}'); alert('No Rekening {{ $item->bank }} disalin!');"
                                                    title="Salin No Rekening">
                                                    <i class="ti ti-copy"></i>
                                                    Salin
                                                </button>
                                            </div>
                                        </li>
                                    @empty
                                        {{-- Tampilan jika Admin belum input rekening / semua non-aktif --}}
                                        <li class="list-group-item text-center text-danger">
                                            <i class="ti ti-alert-circle"></i> Belum ada rekening bank tersedia.
                                        </li>
                                    @endforelse
                                </ul>

                                <p class="small text-muted">
                                    *Setelah transfer, silakan upload bukti pembayaran pada formulir di samping.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Konfirmasi Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('payment.process', $pesanan->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    {{-- Metode Pembayaran (Dropdown/Radio) --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Transfer ke Bank Mana?</label>
                                        <select name="metode_pembayaran" class="form-select" required>
                                            <option value="">-- Pilih Bank Tujuan --</option>

                                            @foreach ($banks as $item)
                                                {{-- Value: Nama Bank (sesuai database pesanan kamu) --}}
                                                {{-- Teks: Bank - No Rek - Atas Nama --}}
                                                <option value="{{ $item->bank }}">
                                                    {{ $item->bank }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    {{-- Nama Pengirim --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nama Pemilik Rekening (Pengirim)</label>
                                        <input type="text" name="nama_akun_bank" class="form-control"
                                            placeholder="Contoh: Budi Santoso" required>
                                    </div>

                                    {{-- Upload Bukti --}}
                                    <div class="mb-3">
                                        <label class="form-label">Upload Bukti Transfer</label>
                                        <input type="file" name="bukti_bayar" class="form-control" accept="image/*"
                                            required>
                                        <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 py-2">
                                        <i class="fa-solid fa-upload me-2"></i> Kirim Bukti Bayar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
