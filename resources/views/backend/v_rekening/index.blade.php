@extends('backend.v_layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mb-4">
            <h3>Manajemen Rekening Bank</h3>
            <p class="text-muted">Kelola nomor rekening untuk tujuan transfer pembayaran.</p>
        </div>

        {{-- KOLOM KIRI: Form Tambah --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="ti ti-plus me-1"></i> Tambah Rekening
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.rekening.store') }}" method="POST">
                        @csrf

                        {{-- Input Nama Bank --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Bank</label>
                            <input type="text" name="bank"
                                class="form-control text-uppercase @error('bank') is-invalid @enderror"
                                placeholder="Contoh: BCA" value="{{ old('bank') }}" required>

                            {{-- Pesan Error --}}
                            @error('bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Nomor Rekening --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Rekening</label>
                            <input type="number" name="no_rekening"
                                class="form-control @error('no_rekening') is-invalid @enderror"
                                placeholder="Contoh: 1234567890" value="{{ old('no_rekening') }}" required>

                            {{-- Pesan Error --}}
                            @error('no_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Atas Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Atas Nama</label>
                            <input type="text" name="atas_nama"
                                class="form-control text-uppercase @error('atas_nama') is-invalid @enderror"
                                placeholder="Contoh: ZIZI FLORIST" value="{{ old('atas_nama') }}" required>

                            {{-- Pesan Error --}}
                            @error('atas_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-device-floppy me-2"></i> Simpan Rekening
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Daftar Rekening --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bank</th>
                                    <th>No. Rekening</th>
                                    <th>Atas Nama</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekening as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        {{-- Kolom Bank --}}
                                        <td>
                                            <span class="fw-bold text-uppercase">{{ $row->bank }}</span>
                                        </td>

                                        {{-- Kolom No Rekening --}}
                                        <td class="fs-4 fw-bold font-monospace">{{ $row->no_rekening }}</td>

                                        {{-- Kolom Atas Nama --}}
                                        <td style="text-transform: uppercase">{{ $row->atas_nama }}</td>

                                        {{-- Kolom Status (is_active) --}}
                                        <td>
                                            @if ($row->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Non-Aktif</span>
                                            @endif
                                        </td>

                                        {{-- Tombol Hapus --}}
                                        <td>
                                            <form action="{{ route('admin.rekening.destroy', $row->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                                    data-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted p-4">
                                            <i class="ti ti-wallet-off fs-1 mb-2"></i><br>
                                            Belum ada data rekening.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script SweetAlert (Wajib Ada) --}}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.show_confirm');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Hapus Rekening?',
                        text: "Data akan hilang permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
