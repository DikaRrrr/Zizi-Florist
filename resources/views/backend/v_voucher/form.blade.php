@extends('backend.v_layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white"><i class="ti ti-printer me-2"></i> {{ $judul }}</h5>
                </div>

                <form action="{{ route('admin.voucher.cetak') }}" method="POST" target="_blank">
                    @csrf
                    <div class="card-body">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="ti ti-info-circle fs-4 me-2"></i>
                            <div>Pilih rentang tanggal pembuatan voucher.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Dari Tanggal</label>
                                <input type="date" name="tanggal_awal"
                                    class="form-control @error('tanggal_awal') is-invalid @enderror"
                                    value="{{ old('tanggal_awal', date('Y-m-01')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sampai Tanggal</label>
                                <input type="date" name="tanggal_akhir"
                                    class="form-control @error('tanggal_akhir') is-invalid @enderror"
                                    value="{{ old('tanggal_akhir', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-between">
                        <a href="{{ route('admin.voucher.index') }}" class="btn btn-secondary">
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
