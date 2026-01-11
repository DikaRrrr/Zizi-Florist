<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <title>Zizi Florist</title>
</head>

<body>

    <nav class="navbar">
        <div class="logo">Zizi Florist</div>

        <div class="menu-toggle" id="mobile-menu">
            <i class="fa-solid fa-bars"></i>
        </div>

        {{-- Tambahkan class 'd-flex align-items-center' di sini agar anak-anaknya sejajar vertikal --}}
        <ul class="nav-links d-flex align-items-center gap-3" style="list-style: none; padding: 0; margin: 0;">

            <li><a href="{{ route('frontend.beranda') }}" class="text-decoration-none">Home</a></li>
            <a href="{{ route('frontend.beranda') }}#desktop-content" class="text-decoration-none">Produk</a>
            <li><a href="{{ route('pesanan.index') }}" class="text-decoration-none">Pesanan</a></li>

            {{-- User Profile --}}
            <li>
                {{-- 1. Cek dulu: Apakah User SUDAH Login? --}}
                @auth
                    @if (Auth::user()->role == 'admin')
                        {{-- Jika Login sebagai ADMIN --}}
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none" title="Ke Dashboard">
                            <i class="fa-solid fa-gauge-high"></i>
                        </a>
                    @else
                        {{-- Jika Login sebagai CUSTOMER --}}
                        <a href="{{ route('profile') }}" class="text-decoration-none" title="Profile Saya">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    @endif
                @endauth

                {{-- 2. Jika BELUM Login (Tamu), tampilkan tombol Login --}}
                @guest
                    <a href="{{ route('login') }}" class="text-decoration-none" title="Masuk / Login">
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </a>
                @endguest
            </li>

            {{-- Keranjang Belanja --}}
            <li class="nav-item">
                {{-- Tambahkan d-flex align-items-center di sini juga --}}
                <a class="nav-link position-relative d-flex align-items-center" href="{{ route('keranjang') }}">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 1.2rem;"></i>

                    {{-- BADGE JUMLAH KERANJANG --}}
                    @if (session('cart'))
                        <span class="start-100 translate-middle badge rounded-pill bg-danger"
                            style="top: 5px; font-size: 0.6rem;">
                            {{ count(session('cart')) }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </a>
            </li>
        </ul>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="desktop-footer">
        <div class="footer-container">
            <div class="footer-section brand-info">
                <h3>Bloomscape</h3>
                <p>Spesialis penyedia tanaman hias dan bunga segar berkualitas premium untuk memperindah ruang Anda.</p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-section contact-info">
                <h3>Kontak Kami <i class="fa-solid fa-phone"></i></h3>
                <ul>
                    <li><strong>Telepon:</strong> (021) 123-4567</li>
                    <li><strong>WhatsApp:</strong> +62 812 3456 7890</li>
                    <li><strong>Email:</strong> info@bloomscape.com</li>
                    <li><strong>Jam Kerja:</strong> Sen-Jum, 09:00 - 18:00 WIB</li>
                </ul>
            </div>

            <div class="footer-section location-info">
                <h3>Alamat Toko <i class="fa-solid fa-location-dot"></i></h3>
                <p>
                    Jl. Kebun Raya No. 10, <br />
                    Kecamatan Mekar, Kota Bunga,<br />
                    12345, Indonesia.
                </p>
                <p>
                    <a href="#">Lihat di Google Maps</a>
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Bloomscape. All Right Reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/notification.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500,
                // Style custom Zizi Florist kamu (opsional)
                color: '#6c4a3a',
                background: '#fff',
                iconColor: '#ff96ac'
            });
        </script>
    @endif
</body>

</html>
