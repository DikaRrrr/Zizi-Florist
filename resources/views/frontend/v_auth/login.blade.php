@extends('frontend.v_layouts.app') @section('content')
    <main class="login-page">
        <div class="login-container">
            <div class="decor-blob decor-blob-1"></div>
            <div class="decor-blob decor-blob-2"></div>

            <div class="login-box">
                <h2 class="welcome-heading">
                    <span class="pre-text">Sebelum belanja login dulu yaaa</span>
                    Selamat Datang
                </h2>

                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf

                    {{-- 1. ALERT GLOBAL (Muncul jika Login Gagal) --}}
                    {{-- Ini solusi agar user tidak bingung field mana yang salah --}}
                    @if ($errors->any())
                        <div class="alert alert-danger fade show mb-4 text-center" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <small><strong>Login Gagal!</strong> Email atau Password salah.</small>
                        </div>
                    @endif

                    {{-- 2. INPUT EMAIL --}}
                    <div class="mb-3">
                        <div class="input-group">
                            {{-- Hapus class 'is-invalid' agar tidak merah saat login gagal --}}
                            {{-- Kita hanya merahkan jika field KOSONG (client side required) --}}
                            <input class="login-input" placeholder="Masukan Alamat Email" type="email" name="email"
                                value="{{ old('email') }}" required>
                        </div>
                    </div>

                    {{-- 3. INPUT PASSWORD --}}
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" name="password" id="passwordInput"
                                class="login-input form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan Password" required>

                            {{-- Tombol Mata (Toggle) --}}
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                style="border-color: #ccc;">
                                <i class="fa-solid fa-eye" id="iconEye"></i>
                            </button>
                        </div>
                    </div>

                    {{-- TOMBOL LOGIN --}}
                    <button class="btn login-btn w-100 mt-2" type="submit">Masuk</button>
                </form>

                <p class="signup-prompt">tidak memiliki akun? <a href="{{ route('register') }}" class="signup-link">buat
                        akun</a></p>
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
