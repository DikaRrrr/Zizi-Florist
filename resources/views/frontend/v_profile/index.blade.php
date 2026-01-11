@extends('frontend.v_layouts.app') @section('content')
    <main class="desktop-content account-page-content">
        <div class="account-container">
            <h1 class="page-title">Profil Pengguna</h1>

            <div class="profile-header">
                <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data"
                    id="avatar-form">
                    @csrf
                    @method('PATCH')

                    {{-- 1. Input File Rahasia (Hidden) --}}
                    {{-- Input ini tidak terlihat, tapi akan dipicu oleh klik pada ikon pensil --}}
                    <input type="file" name="avatar" id="avatar-input" style="display: none;"
                        accept="image/png, image/jpeg, image/jpg">

                    <div class="profile-avatar">
                        {{-- Gambar Avatar Saat Ini (Diberi ID agar mudah diakses JS) --}}
                        <img id="avatar-preview"
                            src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=random' }}"
                            alt="User Avatar" class="avatar-img" />

                        {{-- 2. Ikon Pensil sebagai Pemicu --}}
                        {{-- Saat diklik, dia akan "meng-klik" input file rahasia di atas --}}
                        <div class="edit-avatar-icon" onclick="document.getElementById('avatar-input').click();"
                            style="cursor: pointer;">
                            <i class="fa-solid fa-pencil"></i>
                        </div>
                    </div>
                </form>

                {{-- Tampilkan Error jika file tidak valid --}}
                @error('avatar')
                    <small class="text-danger d-block text-center mt-2">{{ $message }}</small>
                @enderror
                <div class="profile-info">
                    <span class="profile-name">{{ $user->nama }}</span>
                    <span class="profile-email">{{ $user->email }}</span>
                </div>
            </div>

            <div class="account-menu">
                <div class="menu-item dropdown-toggle" data-target="account-details">
                    <span>Akun</span>
                </div>
                <div id="account-details" class="dropdown-content">
                    <div class="detail-item">
                        <span class="detail-label">Nama</span>
                        <span class="detail-value">{{ $user->nama }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">{{ $user->email }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nomor Telepon</span>
                        <span class="detail-value">{{ $user->hp }}</span>
                    </div>
                </div>

                <div class="menu-item dropdown-toggle" data-target="address-details">
                    <span>Alamat Saya</span>
                </div>
                <div id="address-details" class="dropdown-content">
                    <div class="detail-item-address">
                        <p>
                            <strong> <i class="fa-solid fa-location-dot"></i> Rumah Utama</strong>
                        </p>
                        <p>{{ $user->alamat }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="btn logout-btn">Keluar</button>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('avatar-input').addEventListener('change', function() {
            // Cek apakah ada file yang dipilih
            if (this.files && this.files[0]) {
                // Opsional: Membuat preview gambar sebelum upload (agar terasa cepat)
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);

                // PENTING: Submit form secara otomatis setelah file dipilih
                document.getElementById('avatar-form').submit();
            }
        });
    </script>
@endsection
