@extends('backend.v_layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="ti ti-printer me-2"></i> {{ $judul }}
                    </h5>
                </div>

                {{-- PERHATIKAN ACTION INI --}}
                <form action="{{ route('admin.produk.cetak') }}" method="POST" target="_blank">
                    @csrf

                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            <i class="ti ti-info-circle me-2"></i> Silakan pilih rentang tanggal.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Dari Tanggal</label>
                                <input type="date" name="tanggal_awal"
                                    class="form-control @error('tanggal_awal') is-invalid @enderror"
                                    value="{{ old('tanggal_awal', date('Y-m-01')) }}" required>
                                @error('tanggal_awal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sampai Tanggal</label>
                                <input type="date" name="tanggal_akhir"
                                    class="form-control @error('tanggal_akhir') is-invalid @enderror"
                                    value="{{ old('tanggal_akhir', date('Y-m-d')) }}" required>
                                @error('tanggal_akhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-between">
                        {{-- Sesuaikan route index produk kamu --}}
                        {{-- Asumsi route index produk adalah 'backend.produk.index' atau 'admin.produk.index' --}}
                        {{-- Cek php artisan route:list jika ragu --}}
                        <a href="{{ url('admin/produk') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-printer"></i> Cetak Sekarang
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
