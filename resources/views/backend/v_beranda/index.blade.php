@extends('backend.v_layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body border-top">
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ALERT SELAMAT DATANG --}}
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">
                            Selamat Datang, {{ Auth::user()->name ?? Auth::user()->nama }}
                        </h4>

                        Dengan hak akses yang anda miliki sebagai
                        <b>
                            @if (Auth::user()->role == 'admin' || Auth::user()->role == 1)
                                Super Admin
                            @elseif(Auth::user()->role == 'user' || Auth::user()->role == 0)
                                Pelanggan
                            @else
                                {{ Auth::user()->role }}
                            @endif
                        </b>
                        ini adalah halaman admin dari Website Zizi Florist.

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIK DASHBOARD --}}
    <div class="row">

        {{-- CARD 1: TOTAL PRODUK --}}
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Total Produk</h6>
                    <h4 class="mb-3">
                        {{ $totalProduk }}
                        <span class="badge bg-light-primary border border-primary">
                            <i class="ti ti-plant-2"></i> Bunga
                        </span>
                    </h4>
                </div>
            </div>
        </div>

        {{-- CARD 2: TOTAL PELANGGAN --}}
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Total Pelanggan</h6>
                    <h4 class="mb-3">
                        {{ $totalUser }}
                        <span class="badge bg-light-success border border-success">
                            <i class="ti ti-users"></i> Orang
                        </span>
                    </h4>
                </div>
            </div>
        </div>

        {{-- CARD 3: PESANAN (Total & Perlu Proses) --}}
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Total Pesanan</h6>
                    <h4 class="mb-3">
                        {{ $totalPesanan }}
                        <span class="badge bg-light-warning border border-warning">
                            <i class="ti ti-shopping-cart"></i> Transaksi
                        </span>
                    </h4>

                    {{-- Tambahan: Info Pesanan Pending --}}
                    @if ($perluDiproses > 0)
                        <small class="text-danger fw-bold">
                            <i class="ti ti-bell-ringing"></i> {{ $perluDiproses }} Perlu Konfirmasi!
                        </small>
                    @else
                        <small class="text-success">
                            <i class="ti ti-check"></i> Semua aman
                        </small>
                    @endif
                </div>
            </div>
        </div>

        {{-- CARD 4: PENDAPATAN --}}
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Total Pendapatan</h6>
                    <h4 class="mb-3">
                        {{-- Format Rupiah Dinamis --}}
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}

                        <span class="badge bg-light-danger border border-danger">
                            <i class="ti ti-trending-up"></i>
                        </span>
                    </h4>
                    <small class="text-muted">Dari pesanan lunas</small>
                </div>
            </div>
        </div>

    </div>
@endsection
