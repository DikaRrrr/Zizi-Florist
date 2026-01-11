@extends('backend.v_layouts.app')

@section('content')
    <form action="{{ route('admin.voucher.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-12">
                {{-- Header & Breadcrumb --}}
                <div class="card mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.voucher.index') }}">Kelola Voucher</a></li>
                            <li class="breadcrumb-item" aria-current="page">Buat Baru</li>
                        </ul>
                        
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('admin.voucher.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- KOLOM KIRI: Data Utama Voucher --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Voucher</h5>
                    </div>
                    <div class="card-body">
                        
                        {{-- Input Kode Voucher --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kode Voucher <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="kode_voucher" id="kode_voucher" 
                                       class="form-control text-uppercase @error('kode_voucher') is-invalid @enderror"
                                       placeholder="Contoh: MERDEKA45" value="{{ old('kode_voucher') }}" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                    <i class="ti ti-refresh"></i> Auto
                                </button>
                            </div>
                            <small class="text-muted">Gunakan huruf kapital dan angka tanpa spasi.</small>
                            @error('kode_voucher')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            {{-- Pilih Tipe Diskon --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipe Diskon</label>
                                <select name="tipe" class="form-select" id="tipe_diskon">
                                    <option value="fixed">Potongan Harga (Rp)</option>
                                    <option value="percent">Persentase (%)</option>
                                </select>
                            </div>

                            {{-- Input Jumlah Potongan --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jumlah Potongan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="prefix_jumlah">Rp</span>
                                    <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                                           placeholder="0" value="{{ old('jumlah') }}" required>
                                </div>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Input Minimal Belanja --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Minimal Belanja (Rp)</label>
                            <input type="number" name="min_belanja" class="form-control" 
                                   placeholder="Contoh: 50000 (Isi 0 jika tanpa minimal)" value="{{ old('min_belanja') }}">
                            <small class="text-muted">Syarat total belanja agar voucher bisa dipakai.</small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Pengaturan & Tanggal --}}
            <div class="col-lg-4">
                
                {{-- Card Masa Berlaku --}}
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Masa Berlaku</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Berakhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control" required>
                        </div>
                    </div>
                </div>

                {{-- Card Status & Simpan --}}
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Status & Simpan</h5>
                    </div>
                    <div class="card-body">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Voucher</label>
                            <select name="status" class="form-select">
                                <option value="aktif">Aktif (Bisa Dipakai)</option>
                                <option value="nonaktif">Non-Aktif (Disembunyikan)</option>
                            </select>
                        </div>
                        <hr>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-device-floppy"></i> Simpan Voucher
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    {{-- Script Kecil untuk UX --}}
    <script>
        // 1. Script Auto Generate Kode Acak
        function generateCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('kode_voucher').value = result;
        }

        // 2. Script Ubah Label Rp/% saat tipe diganti
        const tipeSelect = document.getElementById('tipe_diskon');
        const prefixSpan = document.getElementById('prefix_jumlah');

        tipeSelect.addEventListener('change', function() {
            if (this.value === 'persen') {
                prefixSpan.innerText = '%';
            } else {
                prefixSpan.innerText = 'Rp';
            }
        });
    </script>
@endsection