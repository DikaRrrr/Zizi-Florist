@extends('backend.v_layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">Tambah User Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>

                                {{-- Gunakan Input Group agar tombol mata menempel dengan input --}}
                                <div class="input-group">

                                    {{-- Input Password (Tambahkan ID="passwordInput") --}}
                                    <input type="password" name="password" id="passwordInput"
                                        class="form-control @error('password') is-invalid @enderror" required>

                                    {{-- Tombol Mata (Trigger) --}}
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="ti ti-eye"></i>
                                    </button>

                                    {{-- Error Message --}}
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role (Hak Akses)</label>
                                <select name="role" class="form-select">
                                    <option value="customer">Customer (User)</option>
                                    <option value="admin">Administrator</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="number" name="hp" class="form-control" value="{{ old('hp') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const passwordInput = document.querySelector('#passwordInput');
            const icon = togglePassword.querySelector('i');

            togglePassword.addEventListener('click', function() {
                // 1. Cek tipe saat ini (password atau text?)
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';

                // 2. Ubah tipe input
                passwordInput.setAttribute('type', type);

                // 3. Ubah Ikon (Mata Terbuka / Coret)
                if (type === 'text') {
                    icon.classList.remove('ti-eye');
                    icon.classList.add('ti-eye-off'); // Ganti icon jadi mata dicoret
                } else {
                    icon.classList.remove('ti-eye-off');
                    icon.classList.add('ti-eye'); // Kembalikan icon mata biasa
                }
            });
        });
    </script>
@endsection
