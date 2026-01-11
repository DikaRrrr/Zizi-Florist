@extends('backend.v_layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Data User</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="alert alert-info">
                            <i class="ti ti-info-circle"></i> Kosongkan password jika tidak ingin menggantinya.
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru (Opsional)</label>

                            <div class="input-group">
                                {{-- Input Password (Beri ID 'newPasswordInput') --}}
                                <input type="password" name="password" id="newPasswordInput" class="form-control"
                                    placeholder="Biarkan kosong jika tetap">

                                {{-- Tombol Mata (Beri ID 'toggleNewPassword') --}}
                                <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer(User)
                                </option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="number" name="hp" class="form-control" value="{{ old('hp', $user->hp) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('#toggleNewPassword');
            const passInput = document.querySelector('#newPasswordInput');
            const icon = toggleBtn.querySelector('i');

            toggleBtn.addEventListener('click', function() {
                // 1. Cek tipe input saat ini
                const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';

                // 2. Ubah tipe input
                passInput.setAttribute('type', type);

                // 3. Ganti Icon Mata
                if (type === 'text') {
                    icon.classList.remove('ti-eye');
                    icon.classList.add('ti-eye-off'); // Mata dicoret
                } else {
                    icon.classList.remove('ti-eye-off');
                    icon.classList.add('ti-eye'); // Mata biasa
                }
            });
        });
    </script>
@endsection
