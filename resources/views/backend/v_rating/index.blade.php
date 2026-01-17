@extends('backend.v_layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-star me-2"></i> {{ $judul }}
                        </h5>

                        {{-- Tombol Cetak --}}
                        <a href="{{ route('admin.rating.formcetak') }}" class="btn btn-success">
                            <i class="ti ti-printer me-2"></i> Cetak Laporan
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr class="table-primary text-center">
                                    <th width="5%">No</th>
                                    <th width="15%">Nama User</th>
                                    <th width="20%">Produk</th>
                                    <th width="10%">Rating</th>
                                    <th>Komentar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rating as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>

                                        {{-- Nama User --}}
                                        <td>
                                            <strong>{{ $row->user->nama ?? 'User Dihapus' }}</strong><br>
                                            <small class="text-muted">{{ $row->created_at->format('d M Y') }}</small>
                                        </td>

                                        {{-- Nama Produk --}}
                                        <td>
                                            <a href="#" target="_blank">
                                                {{ $row->produk->nama_produk ?? 'Produk Dihapus' }}
                                            </a>
                                        </td>

                                        {{-- Bintang (Looping Icon) --}}
                                        <td class="text-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $row->rating)
                                                    <i class="ti ti-star-filled text-warning fs-4"></i>
                                                @else
                                                    <i class="ti ti-star text-muted fs-4"></i>
                                                @endif
                                            @endfor
                                            <div class="text-dark small fw-bold mt-1">({{ $row->rating }}/5)</div>
                                        </td>

                                        {{-- Isi Komentar --}}
                                        <td>
                                            {{ $row->komentar ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const form = this.closest('form'); 

                Swal.fire({
                    title: 'Hapus Ulasan?',
                    text: "Ulasan ini akan dihapus permanen (Moderasi).",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection
