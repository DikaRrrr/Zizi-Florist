@extends('backend.v_layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">

            {{-- Header & Tombol Aksi --}}
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">Kelola Voucher</li>
                    </ul>

                    {{-- Tombol Tambah Voucher (Ganti dari Export ke Tambah) --}}
                    <a href="{{ route('admin.voucher.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Buat Voucher Baru
                    </a>
                </div>
            </div>

            {{-- Tabel Data Voucher --}}
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="table-voucher">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Voucher</th>
                                    <th>Potongan Diskon</th>
                                    <th>Min. Belanja</th>
                                    <th>Status</th>
                                    <th>Masa Berlaku</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Pastikan Controller mengirim variabel $vouchers --}}
                                @forelse ($vouchers as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        {{-- Kolom Kode Voucher --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="pc-micon me-2"><i
                                                        class="ti ti-ticket text-warning fs-4"></i></span>
                                                <div>
                                                    <span class="fw-bold text-uppercase">{{ $row->kode }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $row->tipe ?? 'Promo Spesial' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kolom Nilai Diskon --}}
                                        <td>
                                            @if ($row->tipe == 'percent')
                                                <span
                                                    class="badge bg-light text-dark border border-secondary">{{ $row->nilai }}%</span>
                                            @else
                                                <span class="badge bg-light text-primary border border-primary">
                                                    Rp {{ number_format($row->nilai, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Kolom Minimal Belanja --}}
                                        <td>
                                            @if ($row->minimal_pembelian > 0)
                                                Rp {{ number_format($row->minimal_pembelian, 0, ',', '.') }}
                                            @else
                                                <span class="text-muted">Tanpa Minimum</span>
                                            @endif
                                        </td>

                                        {{-- Kolom Status --}}
                                        <td>
                                            @if ($row->is_active == 1)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Non-Aktif</span>
                                            @endif
                                        </td>

                                        {{-- Kolom Tanggal Kadaluarsa --}}
                                        <td>
                                            {{-- Asumsi kolom database: tanggal_mulai & tanggal_akhir --}}
                                            <small>
                                                <i class="ti ti-calendar-event"></i>
                                                {{ \Carbon\Carbon::parse($row->tanggal_akhir)->format('d M Y') }}
                                            </small>
                                        </td>

                                        {{-- Kolom Aksi --}}
                                        <td>
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.voucher.edit', $row->id) }}"
                                                class="btn btn-sm btn-info text-white" title="Edit Voucher">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <form method="POST" action="{{ route('admin.voucher.destroy', $row->id) }}"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')

                                                {{-- HAPUS bagian onsubmit="return confirm..." --}}
                                                {{-- TAMBAHKAN class "show_confirm" --}}
                                                <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                                    data-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center p-4">
                                            <div class="text-muted">
                                                <i class="ti ti-ticket-off fs-1 mb-2"></i><br>
                                                Belum ada voucher yang dibuat.
                                            </div>
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
@endsection
