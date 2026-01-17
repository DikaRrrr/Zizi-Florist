@extends('backend.v_layouts.app')

@section('content')

{{-- 1. FORM PEMBUNGKUS UTAMA --}}
<form action="{{ route('admin.voucher.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-12">
            {{-- Header & Breadcrumb --}}
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.voucher.index') }}">Kelola Voucher</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Baru</li>
                    </ul>
                    
                    <a href="{{ route('admin.voucher.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- ================= KOLOM KIRI (DATA UTAMA) ================= --}}
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">Informasi Voucher</h5>
                </div>
                <div class="card-body">
                    
                    {{-- Input Kode Voucher --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode Voucher <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="kode" id="kode" 
                                   class="form-control text-uppercase @error('kode') is-invalid @enderror"
                                   placeholder="Contoh: MERDEKA45" value="{{ old('kode') }}" required>
                            
                            <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                <i class="ti ti-refresh"></i> Auto
                            </button>
                            
                            {{-- Error Feedback --}}
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Gunakan huruf kapital dan angka tanpa spasi.</small>
                    </div>

                    <div class="row">
                        {{-- Pilih Tipe Diskon --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tipe Diskon</label>
                            <select name="tipe" class="form-select" id="tipe">
                                <option value="fixed" {{ old('tipe') == 'fixed' ? 'selected' : '' }}>Potongan Harga (Rp)</option>
                                <option value="percent" {{ old('tipe') == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                            </select>
                        </div>

                        {{-- Input Jumlah Potongan --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jumlah Potongan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="prefix_nilai">Rp</span>
                                
                                <input type="number" name="nilai" 
                                       class="form-control @error('nilai') is-invalid @enderror" 
                                       placeholder="0" value="{{ old('nilai') }}" required>
                                
                                {{-- Error Feedback (Di dalam input-group) --}}
                                @error('nilai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Input Minimal Belanja --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Minimal Belanja (Rp)</label>
                        <input type="number" name="minimal_pembelian" class="form-control @error('minimal_pembelian') is-invalid @enderror" 
                               placeholder="Contoh: 50000 (Isi 0 jika tanpa minimal)" value="{{ old('minimal_pembelian') }}">
                        <small class="text-muted">Syarat total belanja agar voucher bisa dipakai.</small>
                        
                        @error('minimal_pembelian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ================= KOLOM KANAN (TANGGAL & STATUS) ================= --}}
        <div class="col-lg-4">
            
            {{-- Card Masa Berlaku --}}
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Masa Berlaku</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" 
                               class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                               value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Berakhir</label>
                        <input type="date" name="tanggal_selesai" 
                               class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                               value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif (Bisa Dipakai)</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif (Disembunyikan)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

{{-- Script JS --}}
@push('scripts')
<script>
    // 1. Script Auto Generate Kode Acak
    function generateCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < 8; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('kode').value = result;
    }

    // 2. Script Ubah Label Rp/% saat tipe diganti
    // Saya pastikan ID nya sesuai dengan elemen HTML di atas
    const tipeSelect = document.getElementById('tipe');
    const prefixSpan = document.getElementById('prefix_nilai');

    tipeSelect.addEventListener('change', function() {
        if (this.value === 'percent') {
            prefixSpan.innerText = '%';
        } else {
            prefixSpan.innerText = 'Rp';
        }
    });
</script>
@endpush

@endsection