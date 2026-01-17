@extends('backend.v_layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Data User</h5>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-light">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $user->name) }}" required>

                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info py-2">
                            <i class="ti ti-info-circle me-1"></i> Kosongkan password jika tidak ingin menggantinya.
                        </div>

                        <div class="row">
                            {{-- Password Baru --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password Baru (Opsional)</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="newPasswordInput"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Biarkan kosong jika tetap">

                                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                        <i class="ti ti-eye"></i>
                                    </button>

                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Role (Hak Akses) <span
                                        class="text-danger">*</span></label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror">
                                    {{-- Logic: Cek input 'old' dulu, kalau tidak ada baru cek database --}}
                                    <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>
                                        Customer (User Biasa)
                                    </option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Administrator
                                    </option>
                                </select>

                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- No HP --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">No HP</label>
                            <input type="number" name="hp" class="form-control @error('hp') is-invalid @enderror"
                                value="{{ old('hp', $user->hp) }}">

                            @error('hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $user->alamat) }}</textarea>

                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-device-floppy me-2"></i> Update Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Show/Hide Password --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleBtn = document.querySelector('#toggleNewPassword');
                const passInput = document.querySelector('#newPasswordInput');
                const icon = toggleBtn.querySelector('i');

                if (toggleBtn && passInput) {
                    toggleBtn.addEventListener('click', function() {
                        // 1. Cek tipe input saat ini
                        const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';

                        // 2. Ubah tipe input
                        passInput.setAttribute('type', type);

                        // 3. Ganti Icon Mata
                        if (type === 'text') {
                            icon.classList.remove('ti-eye');
                            icon.classList.add('ti-eye-off');
                        } else {
                            icon.classList.remove('ti-eye-off');
                            icon.classList.add('ti-eye');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
