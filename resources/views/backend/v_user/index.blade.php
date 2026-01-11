@extends('backend.v_layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Manajemen User</h5>
                        <small class="text-muted">Kelola Admin dan Pelanggan</small>
                    </div>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
                        <i class="ti ti-user-plus"></i> Tambah User
                    </a>
                </div>

                <div class="card-body border-top">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="table-user">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>No HP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{-- Avatar Inisial --}}
                                                <div class="bg-light-primary text-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 35px; height: 35px; font-weight:bold;">
                                                    {{ substr($row->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <span class="fw-bold">{{ $row->name }}</span>
                                                    <br>
                                                    <small class="text-muted">Bergabung:
                                                        {{ $row->created_at->format('d M Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $row->email }}</td>
                                        <td>
                                            @if ($row->role == 'admin' || $row->role == 1)
                                                <span class="badge bg-primary">Administrator</span>
                                            @else
                                                <span class="badge bg-success">Pelanggan</span>
                                            @endif
                                        </td>
                                        <td>{{ $row->hp ?? '-' }}</td>
                                        <td>
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.user.edit', $row->id) }}"
                                                class="btn btn-sm btn-info text-white">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            {{-- Tombol Hapus (Sembunyikan jika user itu sendiri) --}}
                                            @if (Auth::id() != $row->id)
                                                <form action="{{ route('admin.user.destroy', $row->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                                        data-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
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

    {{-- Script SweetAlert (Copy dari sebelumnya) --}}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.show_confirm');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Hapus User?',
                        text: "Data tidak bisa dikembalikan!",
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
