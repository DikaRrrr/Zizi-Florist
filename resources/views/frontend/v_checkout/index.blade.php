@extends('frontend.v_layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-7 mb-4">
                <h2 class="mb-4">Konfirmasi Pesanan</h2>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <form action="{{ route('place.order') }}" method="POST">
                            @csrf

                            <h5 class="mb-3">Informasi Pengiriman</h5>

                            {{-- NAMA PENERIMA --}}
                            <div class="mb-3">
                                <label class="form-label">Nama Penerima</label>
                                <input type="text" name="nama_penerima" class="form-control"
                                    value="{{ Auth::user()->nama }}" required>
                                <small class="text-muted">Pastikan nama sesuai KTP untuk memudahkan kurir.</small>
                            </div>

                            {{-- NOMOR HP (WAJIB ADA sesuai Model Pesanan) --}}
                            <div class="mb-3">
                                <label class="form-label">Nomor HP / WhatsApp</label>
                                {{-- Jika di tabel user ada kolom 'hp', ganti '' dengan Auth::user()->hp --}}
                                <input type="text" name="hp_penerima" class="form-control"
                                    value="{{ Auth::user()->hp ?? '' }}" placeholder="Contoh: 08123456789" required>
                            </div>

                            {{-- ALAMAT LENGKAP --}}
                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat_penerima" class="form-control" rows="4" required
                                    placeholder="Nama Jalan, Nomor Rumah, RT/RW, Kelurahan, Kecamatan...">{{ Auth::user()->alamat ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 mt-3 fw-bold">
                                <i class="fa-solid fa-lock me-2"></i> Buat Pesanan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Ringkasan Pesanan</h4>
                    </div>
                    <div class="card-body">
                        {{-- 1. Looping Barang --}}
                        @if (session('cart'))
                            <ul class="list-group list-group-flush mb-3">
                                @foreach (session('cart') as $id => $details)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div class="d-flex align-items-center">
                                            @if (isset($details['image']))
                                                <img src="{{ asset('storage/' . $details['image']) }}" alt="img"
                                                    width="50" class="me-2 rounded">
                                            @endif
                                            <div>
                                                <h6 class="my-0">{{ $details['name'] }}</h6>
                                                <small class="text-muted">{{ $details['quantity'] }} x Rp
                                                    {{ number_format($details['price'], 0, ',', '.') }}</small>
                                            </div>
                                        </div>
                                        <span class="text-muted">Rp
                                            {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <hr>

                        {{-- 2. Subtotal (Harga Barang Saja) --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="subtotal-display">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        {{-- 3. TAMBAHAN: Ongkos Kirim --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkos Kirim</span>

                            {{-- Logika: Jika ongkir ada nilainya tampilkan, jika tidak tulis "Dihitung nanti" --}}
                            @if (isset($ongkir) && $ongkir > 0)
                                <span id="ongkir-display">Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                            @else
                                <span id="ongkir-display" class="text-muted fst-italic small">Dihitung dari alamat</span>
                            @endif
                        </div>

                        {{-- 4. TAMBAHAN: Diskon (Opsional, jika ada voucher) --}}
                        <div class="d-flex justify-content-between mb-2 text-danger" id="discountRow"
                            style="display: {{ isset($jumlah_diskon) && $jumlah_diskon > 0 ? 'flex' : 'none' }};">

                            <span>Diskon Voucher</span>

                            {{-- ID ini penting untuk diisi angkanya oleh JS --}}
                            <span id="discountAmount">
                                - Rp {{ number_format($jumlah_diskon ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <hr class="my-2">

                        {{-- 5. Total Bayar (Subtotal + Ongkir - Diskon) --}}
                        <div class="d-flex justify-content-between mt-3">
                            <strong class="fs-5">Total Bayar</strong>
                            <strong class="fs-5 text-success" id="grand-total-display">
                                {{-- Pastikan variabel $total_akhir dikirim dari controller --}}
                                {{-- Rumus: $total (subtotal) + $ongkir - $diskon --}}
                                @php
                                    $ongkir_fix = $ongkir ?? 0;
                                    $diskon_fix = $jumlah_diskon ?? 0;
                                    $grand_total = $subtotal + $ongkir_fix - $diskon_fix;
                                @endphp
                                Rp {{ number_format($grand_total, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
