<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Zizi Florist</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <link rel="icon" href="{{ asset('admin-template/dist') }}/assets/images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <link rel="stylesheet" href="{{ asset('admin-template/dist') }}/assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('admin-template/dist') }}/assets/fonts/feather.css">
    <link rel="stylesheet" href="{{ asset('admin-template/dist') }}/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('admin-template/dist') }}/assets/fonts/material.css">
    <link rel="stylesheet" href="{{ asset('admin-template/dist') }}/assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('admin-template/dist') }}/assets/css/style-preset.css">

    <style>
        /* 1. Mengubah Warna Sidebar (Menu Kiri) */
        .pc-sidebar,
        .pc-sidebar .m-header {
            background: #ff96ac !important;
            /* Warna Pink */
        }

        /* 2. Mengubah Warna Header (Navbar Atas) */
        .pc-header {
            background: #ff96ac !important;
            /* Warna Pink */
            color: #ffffff;
            /* Teks jadi putih biar kontras */
        }

        /* 3. Mengubah Warna Teks Menu di Sidebar agar terlihat jelas */
        .pc-sidebar .pc-link {
            color: #ffffff !important;
            /* Teks menu putih */
        }

        /* 4. Mengubah Warna Menu saat Disorot (Hover) atau Aktif */
        .pc-sidebar .pc-item.active>.pc-link,
        .pc-sidebar .pc-link:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            /* Putih transparan */
            color: #fff !important;
        }

        /* 5. Mengubah Ikon Burger Menu & Search di Header jadi Putih */
        .pc-header .pc-head-link i,
        .pc-header .pc-head-link svg {
            color: #ffffff !important;
        }

        /* 6. Perbaikan Logo Area (Pojok Kiri Atas) */
        .pc-sidebar .m-header .b-brand {
            background: transparent !important;
        }

        .zizi-logo {
            font-family: 'Public Sans', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #6c4a3a !important;
            letter-spacing: 1px;
        }
    </style>

</head>
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('admin.dashboard') }}" class="b-brand text-decoration-none">
                    <span class="zizi-logo">Zizi Florist</span>
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Manajemen Toko</label>
                        <i class="ti ti-dashboard"></i>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('admin.produk.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-plant-2"></i></span>
                            <span class="pc-mtext">Produk Bunga</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pesanan.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-shopping-cart"></i></span>
                            <span class="pc-mtext">Pesanan Masuk</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->routeIs('admin.voucher.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.voucher.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-ticket"></i></span>
                            <span class="pc-mtext">Buat Voucher</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.user.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-user"></i></span>
                            <span class="pc-mtext">Manajemen User</span>
                        </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('admin.rekening.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.rekening.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-wallet"></i></span>
                                <span class="pc-mtext">Rekening Bank</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="#" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-printer"></i></span>
                                <span class="pc-mtext">Cetak Laporan</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <a href="#" onclick="this.closest('form').submit()" class="pc-link">
                                    <span class="pc-micon"><i class="ti ti-power"></i></span>
                                    <span class="pc-mtext">Logout</span>
                                </a>
                            </form>
                        </li>
                </ul>
            </div>
        </div>
    </nav>
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="ms-auto">
                <ul class="list-unstyled">

                    {{-- ================================================== --}}
                    {{-- 1. TOMBOL KE FRONTEND (BARU) --}}
                    {{-- ================================================== --}}
                    <li class="pc-h-item">
                        {{-- Tambahkan class 'd-flex align-items-center' agar icon dan teks sejajar vertikal --}}
                        <a class=" me-0 d-flex align-items-center" href="{{ route('frontend.beranda') }}"
                            target="_blank" title="Lihat Toko">

                            {{-- Teks Website --}}
                            <span class="d-none d-sm-inline ms-2 fw-bold" style="font-size: 14px;">Lihat Toko</span>
                        </a>
                    </li>

                    {{-- ================================================== --}}
                    {{-- 2. PROFIL ADMIN (YANG LAMA) --}}
                    {{-- ================================================== --}}
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside"
                            aria-expanded="false">
                            <img src="{{ asset('admin-template/dist') }}/assets/images/user/avatar-2.jpg"
                                alt="user-image" class="user-avtar">
                            <span>{{ Auth::user()->nama }}</span>
                        </a>

                        {{-- DROPDOWN MENU ADMIN (Biasanya ada menu logout disini) --}}
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('admin-template/dist') }}/assets/images/user/avatar-2.jpg"
                                            alt="user-image" class="user-avtar wid-35 hei-35">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ Auth::user()->nama }}</h6>
                                        <span>Administrator</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </header>
    <div class="pc-container">
        <div class="pc-content">

            @yield('content')

        </div>
    </div>
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm my-1">
                    <p class="m-0">Zizi Florist Admin Panel</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('admin-template/dist') }}/assets/js/plugins/apexcharts.min.js"></script>
    <script src="{{ asset('admin-template/dist') }}/assets/js/pages/dashboard-default.js"></script>
    <script src="{{ asset('admin-template/dist') }}/assets/js/plugins/popper.min.js"></script>
    <script src="{{ asset('admin-template/dist') }}/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{ asset('admin-template/dist') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{ asset('admin-template/dist') }}/assets/js/fonts/custom-font.js"></script>
    <script src="{{ asset('admin-template/dist') }}/assets/js/pcoded.js"></script>
    <script src="{{ asset('admin-template/dist') }}/assets/js/plugins/feather.min.js"></script>
    <script>
        layout_change('light');
    </script>
    <script>
        change_box_container('false');
    </script>
    <script>
        layout_rtl_change('false');
    </script>
    <script>
        preset_change("preset-1");
    </script>
    <script>
        font_change("Public-Sans");
    </script>

    @stack('scripts')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="{{ asset('backend/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. CEK NOTIFIKASI SUKSES DARI CONTROLLER
        // Kodingan ini akan ngecek: "Apakah ada session bernama 'success'?"
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000 // Otomatis tutup setelah 2 detik
            });
        @endif

        // 2. KONFIRMASI HAPUS (DELETE)
        // Kodingan ini menunggu user klik tombol dengan class ".show_confirm"
        $(document).ready(function() {
            $('.show_confirm').click(function(event) {
                var form = $(this).closest("form"); // Ambil form terdekat
                event.preventDefault(); // Cegah form langsung kirim

                Swal.fire({
                    title: 'Yakin mau dihapus?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Kirim form jika user klik Ya
                    }
                });
            });
        });
    </script>
</body>

</html>
</body>

</html>
