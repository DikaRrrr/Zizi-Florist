@extends('backend.v_layouts.app')

@section('content')
    {{-- Form mengarah ke Route Update dengan method PUT --}}
    <form action="{{ route('admin.voucher.update', $voucher->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-12">
                {{-- Header & Breadcrumb --}}
                <div class="card mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.voucher.index') }}">Kelola Voucher</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Voucher</li>
                        </ul>
                        
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('admin.voucher.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- KOLOM KIRI: Data Utama --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Edit Informasi Voucher</h5>
                    </div>
                    <div class="card-body">
                        
                        {{-- Input Kode Voucher --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kode Voucher <span class="text-danger">*</span></label>
                            <div class="input-group">
                                {{-- value: old('input_name', $model->column_name) --}}
                                <input type="text" name="kode" id="kode" 
                                       class="form-control text-uppercase @error('kode') is-invalid @enderror"
                                       value="{{ old('kode', $voucher->kode) }}" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                    <i class="ti ti-refresh"></i> Ganti Kode
                                </button>
                            </div>
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Pilih Tipe Diskon --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipe Diskon</label>
                                <select name="tipe" class="form-select" id="tipe">
                                    <option value="fixed" {{ $voucher->tipe == 'fixed' ? 'selected' : '' }}>Potongan Harga (Rp)</option>
                                    <option value="percent" {{ $voucher->tipe == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                                </select>
                            </div>

                            {{-- Input Jumlah Potongan --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jumlah Potongan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    {{-- Prefix berubah sesuai JS dibawah --}}
                                    <span class="input-group-text" id="prefix_jumlah">
                                        {{ $voucher->tipe == 'percent' ? '%' : 'Rp' }}
                                    </span>
                                    
                                    {{-- Ambil dari kolom 'nilai' --}}
                                    <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" 
                                           value="{{ old('nilai', $voucher->nilai) }}" required>
                                </div>
                                @error('nilai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Input Minimal Belanja --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Minimal Belanja (Rp)</label>
                            {{-- Ambil dari kolom 'minimal_pembelian' --}}
                            <input type="number" name="minimal_pembelian" class="form-control" 
                                   value="{{ old('minimal_pembelian', $voucher->minimal_pembelian) }}">
                            <small class="text-muted">Isi 0 jika tanpa minimal belanja.</small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Tanggal & Status --}}
            <div class="col-lg-4">
                
                {{-- Card Masa Berlaku --}}
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Masa Berlaku</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" 
                                   value="{{ old('tanggal_mulai', $voucher->tanggal_mulai) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Berakhir</label>
                            {{-- Ambil dari kolom 'tanggal_selesai' --}}
                            <input type="date" name="tanggal_selesai" class="form-control" 
                                   value="{{ old('tanggal_selesai', $voucher->tanggal_selesai) }}" required>
                        </div>
                    </div>
                </div>

                {{-- Card Status --}}
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Status Voucher</h5>
                    </div>
                    <div class="card-body">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                {{-- Logika: Jika is_active == 1 (Aktif), Jika 0 (Nonaktif) --}}
                                <option value="aktif" {{ $voucher->is_active == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ $voucher->is_active == 0 ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-warning w-100">
                            <i class="ti ti-device-floppy"></i> Update Perubahan
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    {{-- Script UX (Sama seperti create) --}}
    <script>
        // 1. Generate Kode
        function generateCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('kode').value = result;
        }

        // 2. Ubah Prefix Rp / %
        const tipeSelect = document.getElementById('tipe');
        const prefixSpan = document.getElementById('nilai');

        tipeSelect.addEventListener('change', function() {
            if (this.value === 'percent') {
                prefixSpan.innerText = '%';
            } else {
                prefixSpan.innerText = 'Rp';
            }
        });
    </script>
@endsection