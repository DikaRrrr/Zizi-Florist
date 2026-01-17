@extends('backend.v_layouts.app')

@section('content')
    {{-- 1. Buka Form di paling luar --}}
    <form action="{{ route('admin.voucher.update', $voucher->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Penting untuk Update --}}

        {{-- 2. Buka ROW (Pembungkus agar Kolom Kiri & Kanan sejajar) --}}
        <div class="row">

            {{-- ================= KOLOM KIRI (DATA UTAMA - 8 Grid) ================= --}}
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
                                <input type="text" name="kode" id="kode"
                                    class="form-control text-uppercase @error('kode') is-invalid @enderror"
                                    value="{{ old('kode', $voucher->kode) }}" required>

                                <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                    <i class="ti ti-refresh"></i> Ganti Kode
                                </button>

                                @error('kode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Pilih Tipe Diskon --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipe Diskon</label>
                                <select name="tipe" class="form-select" id="tipe">
                                    <option value="fixed" {{ $voucher->tipe == 'fixed' ? 'selected' : '' }}>
                                        Potongan Harga (Rp)
                                    </option>
                                    <option value="percent" {{ $voucher->tipe == 'percent' ? 'selected' : '' }}>
                                        Persentase (%)
                                    </option>
                                </select>
                            </div>

                            {{-- Input Jumlah Potongan --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jumlah Potongan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="prefix_nilai">
                                        {{ $voucher->tipe == 'percent' ? '%' : 'Rp' }}
                                    </span>

                                    <input type="number" name="nilai"
                                        class="form-control @error('nilai') is-invalid @enderror"
                                        value="{{ old('nilai', $voucher->nilai) }}" required>

                                    @error('nilai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Input Minimal Belanja --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Minimal Belanja (Rp)</label>
                            <input type="number" name="minimal_pembelian" class="form-control"
                                value="{{ old('minimal_pembelian', $voucher->minimal_pembelian) }}">
                            <small class="text-muted">Isi 0 jika tanpa minimal belanja.</small>
                        </div>

                    </div>
                </div>
            </div>
            {{-- ================= AKHIR KOLOM KIRI ================= --}}


            {{-- ================= KOLOM KANAN (SIDEBAR - 4 Grid) ================= --}}
            <div class="col-lg-4">

                {{-- Card Masa Berlaku --}}
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Masa Berlaku</h5>
                    </div>
                    <div class="card-body">
                        {{-- Tanggal Mulai --}}
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai"
                                class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai', $voucher->tanggal_mulai) }}" required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div class="mb-3">
                            <label class="form-label">Tanggal Berakhir</label>
                            <input type="date" name="tanggal_selesai"
                                class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai', $voucher->tanggal_selesai) }}" required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="aktif"
                                    {{ old('status', $voucher->is_active == 1 ? 'aktif' : 'nonaktif') == 'aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="nonaktif"
                                    {{ old('status', $voucher->is_active == 1 ? 'aktif' : 'nonaktif') == 'nonaktif' ? 'selected' : '' }}>
                                    Non-Aktif
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-warning w-100">
                            <i class="ti ti-device-floppy"></i> Update Perubahan
                        </button>

                        {{-- Tombol Kembali (Opsional) --}}
                        <a href="{{ route('admin.voucher.index') }}" class="btn btn-secondary w-100 mt-2">
                            Batal
                        </a>
                    </div>
                </div>

            </div>
            {{-- ================= AKHIR KOLOM KANAN ================= --}}

        </div> {{-- Penutup ROW (PENTING!) --}}
    </form> {{-- Penutup FORM --}}

    {{-- JS Script (Agar prefix Rp/% berubah) --}}
    @push('scripts')
        <script>
            // Logic ganti Rp ke %
            const tipeSelect = document.getElementById('tipe');
            const prefixSpan = document.getElementById('prefix_nilai');

            tipeSelect.addEventListener('change', function() {
                if (this.value === 'percent') {
                    prefixSpan.innerText = '%';
                } else {
                    prefixSpan.innerText = 'Rp';
                }
            });

            // Logic Generate Kode
            function generateCode() {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let result = '';
                for (let i = 0; i < 8; i++) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                document.getElementById('kode').value = result;
            }
        </script>
    @endpush
@endsection
