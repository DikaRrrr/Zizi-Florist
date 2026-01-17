@extends('frontend.v_layouts.app') @section('content')
    <main class="login-page">
        <div class="login-container">
            <div class="decor-blob decor-blob-1"></div>
            <div class="decor-blob decor-blob-2"></div>

            <div class="login-box">
                <h2 class="welcome-heading">Buat Akun</h2>

                {{-- Pastikan HANYA ADA SATU tag <form> --}}
                <form action="{{ route('register.submit') }}" method="POST">
                    @csrf

                    {{-- 1. NAMA LENGKAP --}}
                    <div class="mb-3">
                        <div class="input-group">
                            {{-- Tambahkan class 'form-control' agar is-invalid (merah) berfungsi --}}
                            <input type="text" name="nama"
                                class="form-control login-input @error('nama') is-invalid @enderror"
                                placeholder="Masukkan Nama Lengkap" value="{{ old('nama') }}" required>
                        </div>
                        @error('nama')
                            <div class="invalid-feedback d-block text-start mt-1">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    {{-- 2. EMAIL --}}
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="email" name="email"
                                class="form-control login-input @error('email') is-invalid @enderror"
                                placeholder="Masukkan Email" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block text-start mt-1">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    {{-- 3. ALAMAT --}}
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" name="alamat"
                                class="form-control login-input @error('alamat') is-invalid @enderror"
                                placeholder="Masukkan Alamat Lengkap" value="{{ old('alamat') }}" required>
                        </div>
                        @error('alamat')
                            <div class="invalid-feedback d-block text-start mt-1">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    {{-- 4. NOMOR HP --}}
                    <div class="mb-3">
                        <div class="input-group">
                            {{-- Gunakan type="text" atau "number" --}}
                            <input type="number" name="hp"
                                class="form-control login-input @error('hp') is-invalid @enderror"
                                placeholder="Masukkan Nomor Hp (Contoh: 0812...)" value="{{ old('hp') }}" required>
                        </div>
                        @error('hp')
                            <div class="invalid-feedback d-block text-start mt-1">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    {{-- 5. PASSWORD --}}
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" name="password" id="passwordInput"
                                class="form-control login-input @error('password') is-invalid @enderror"
                                placeholder="Masukkan Password (Min. 8 Karakter)" required>

                            {{-- Tombol Mata --}}
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                style="border-color: #ccc;">
                                {{-- Pastikan kamu sudah install FontAwesome --}}
                                <i class="fa-solid fa-eye" id="iconEye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block text-start mt-1">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn login-btn w-100 mt-2">Daftar Sekarang</button>
                </form>

                <p class="signup-prompt">sudah punya akun? <a href="{{ route('login') }}" class="signup-link">masuk</a>
                </p>
            </div>
        </div>
    </main>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordInput');
        const icon = document.querySelector('#iconEye');

        togglePassword.addEventListener('click', function(e) {
            // 1. Cek tipe saat ini (password atau text?)
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';

            // 2. Ubah tipe inputnya
            password.setAttribute('type', type);

            // 3. Ubah Ikon (Mata Terbuka <-> Mata Dicoret)
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash'); // Ikon mata dicoret
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye'); // Ikon mata biasa
            }
        });
    </script>
@endsection
