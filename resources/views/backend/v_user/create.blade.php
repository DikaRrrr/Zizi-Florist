@extends('backend.v_layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Tambah User Baru</h5>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-light text-primary">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.user.store') }}" method="POST">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>

                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="contoh@email.com" required>

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Password --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="passwordInput"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Minimal 8 karakter" required>

                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="ti ti-eye"></i>
                                    </button>

                                    {{-- Error Password ditaruh di sini agar rapi --}}
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
                                    <option value="" disabled selected>-- Pilih Role --</option>
                                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer
                                        (User Biasa)</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator
                                    </option>
                                </select>

                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- No HP --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">No HP / WhatsApp</label>
                            <input type="number" name="hp" class="form-control @error('hp') is-invalid @enderror"
                                value="{{ old('hp') }}" placeholder="08xxxxxxxxxx">

                            @error('hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3"
                                placeholder="Masukkan alamat lengkap pengiriman...">{{ old('alamat') }}</textarea>

                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        {{-- Tombol Simpan --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-2"></i> Simpan User Baru
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const togglePassword = document.querySelector('#togglePassword');
                const passwordInput = document.querySelector('#passwordInput');
                const icon = togglePassword.querySelector('i');

                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    if (type === 'text') {
                        icon.classList.remove('ti-eye');
                        icon.classList.add('ti-eye-off');
                    } else {
                        icon.classList.remove('ti-eye-off');
                        icon.classList.add('ti-eye');
                    }
                });
            });
        </script>
    @endpush
@endsection
