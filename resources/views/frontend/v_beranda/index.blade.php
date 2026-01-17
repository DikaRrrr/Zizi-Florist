@extends('frontend.v_layouts.app')

@section('content')

    {{-- 1. HERO SECTION --}}
    <section class="hero">
        <div class="hero-text">
            <h1>
                Love is like a flower<br />
                blooming in its own time.
            </h1>
            <a href="#desktop-content" class="btn scroll-icon">
                <i class="fa-solid fa-chevron-down"></i>
            </a>
        </div>
    </section>

    <main class="desktop-content" id="desktop-content">

        {{-- 2. WELCOME & SEARCH BAR --}}
        <section class="welcome-bar">
            <div class="welcome-text">
                <h1>Selamat Datang di ZIZI FLORIST</h1>
            </div>

            <form action="{{ route('produk.search') }}#hasil-pencarian" method="GET"
                class="search-bar-container welcome-search">
                <input type="text" name="keyword" placeholder="Cari bunga..." class="search-input"
                    value="{{ request('keyword') }}" />
                <button type="submit" class="search-button">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </section>

        @if (isset($promo) && $promo->count() > 0)
            <section class="container my-5">
                <div class="d-flex align-items-center mb-3 justify-content-center">
                    <i class="fa-solid fa-ticket fs-2 text-warning me-2"></i>
                    <h2 class="section-title mb-0">Voucher Spesial Hari Ini!</h2>
                </div>

                <div class="row justify-content-center">
                    @foreach ($promo as $v)
                        <div class="col-md-4 col-sm-6 mb-3">
                            {{-- Style Card Voucher --}}
                            <div class="card h-100 shadow-sm"
                                style="border: 2px dashed #ff96ac; background-color: #fff5f7;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">

                                        {{-- Info Diskon --}}
                                        <div>
                                            <h5 class="fw-bold text-danger mb-1">
                                                @if ($v->tipe == 'percent')
                                                    Diskon {{ $v->nilai }}%
                                                @else
                                                    Potongan Rp {{ number_format($v->nilai, 0, ',', '.') }}
                                                @endif
                                            </h5>
                                            <p class="mb-1 text-dark" style="font-size: 0.9rem;">
                                                Min. Belanja: <strong>Rp
                                                    {{ number_format($v->minimal_pembelian, 0, ',', '.') }}</strong>
                                            </p>
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                <i class="fa-regular fa-clock"></i> Berlaku s/d
                                                {{ \Carbon\Carbon::parse($v->tanggal_selesai)->format('d M Y') }}
                                            </small>
                                        </div>

                                        {{-- Kode & Tombol --}}
                                        <div class="text-end">
                                            <div class="bg-white border border-danger text-danger px-2 py-1 rounded fw-bold mb-2 text-center"
                                                style="font-family: monospace; font-size: 1.1rem;">
                                                {{ $v->kode }}
                                            </div>
                                            <button class="btn btn-sm btn-danger w-100"
                                                onclick="copyVoucher('{{ $v->kode }}')">
                                                <i class="fa-regular fa-copy"></i> Salin
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
        {{-- AKHIR SECTION VOUCHER --}}

        {{-- 4. BEST SELLER SECTION --}}
        <section class="bestseller-section">
            <h2 class="section-title">Best Seller 🔥</h2>

            <div class="bestseller-carousel">
                @foreach ($best_sellers as $item)
                    <div class="bestseller-item">
                        @php
                            $rataRata = $item->rating->avg('rating') ?? 0;
                            $jumlahUlasan = $item->rating->count();
                        @endphp

                        <a href="{{ route('produk.detail', $item->id) }}" style="text-decoration: none; color: inherit;">
                            {{-- GAMBAR --}}
                            @if ($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}"
                                    class="bestseller-image" />
                            @else
                                <img src="https://placehold.co/300x300?text=No+Image" alt="No Image"
                                    class="bestseller-image" />
                            @endif

                            <div class="bestseller-details">
                                <span class="product-name">{{ $item->nama_produk }}</span>
                                <small style="display:block; font-size: 0.8rem; color: #888;">
                                    Terjual: {{ $item->terjual }}
                                </small>

                                {{-- Rating di Best Seller (Opsional) --}}
                                <div class="text-warning small mt-1">
                                    <i class="fa-solid fa-star"></i> {{ number_format($rataRata, 1) }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- 5. PRODUCT LIST SECTION --}}
        <section class="product-list-section" id="hasil-pencarian">

            {{-- Grid Produk --}}
            <div class="product-grid">
                @foreach ($produk as $row)
                    @php
                        // Hitung rating untuk setiap produk di list utama
                        $rataRataUtama = $row->rating->avg('rating') ?? 0;
                        $jumlahUlasanUtama = $row->rating->count();
                    @endphp

                    <a href="{{ route('produk.detail', $row->id) }}">
                        <div class="product-card">

                            {{-- FOTO --}}
                            @if ($row->foto)
                                <img src="{{ asset('storage/' . $row->foto) }}" alt="{{ $row->nama_produk }}"
                                    class="product-image">
                            @else
                                <img src="https://placehold.co/300x300?text=No+Image" alt="No Image" class="product-image">
                            @endif

                            <div class="product-info">
                                <div class="product-header-line">
                                    <span class="product-name">{{ $row->nama_produk }}</span>

                                    {{-- RATING DINAMIS --}}
                                    <span class="rating text-warning">
                                        <i class="fa-solid fa-star" style="color: #FFD43B;"></i>
                                        <span class="fw-bold text-dark">{{ number_format($rataRataUtama, 1) }}</span>
                                    </span>
                                </div>

                                <div class="price-sold-line">
                                    <span class="price">Rp. {{ number_format($row->harga, 0, ',', '.') }}</span>
                                    <span class="sold-count">{{ $row->terjual }} Sold</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            {{-- Akhir Product Grid --}}

            {{-- EMPTY STATE (Jika Kosong) --}}
            @if ($produk->isEmpty())
                <div class="cart-empty-state" style="text-align: center; margin-top: 50px;">
                    <i class="fa-solid fa-box-open empty-icon"
                        style="font-size: 5rem; color: #ff96ac; margin-bottom: 20px;"></i>
                    <p class="empty-message">Tidak ada produk ditemukan.</p>
                </div>
            @endif

            {{-- PAGINATION --}}
            <div class="pagination-container mt-4 d-flex justify-content-center">
                {{ $produk->appends(['keyword' => request('keyword')])->fragment('hasil-pencarian')->links('pagination::bootstrap-5') }}
            </div>

        </section>

    </main>

    {{-- 6. JAVASCRIPT --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function copyVoucher(kode) {
            navigator.clipboard.writeText(kode);
            Swal.fire({
                icon: 'success',
                title: 'Kode Disalin!',
                text: 'Kode ' + kode + ' siap digunakan saat checkout.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const heroImages = [
                "{{ asset('img/hero-7.jpg') }}",
                "{{ asset('img/hero-1.jpg') }}",
                "{{ asset('img/hero-2.jpg') }}",
            ];

            const heroElement = document.querySelector(".hero");
            let currentImageIndex = 0;
            const intervalTime = 5000;

            function changeBackgroundImage() {
                currentImageIndex = (currentImageIndex + 1) % heroImages.length;
                heroElement.style.backgroundImage = `url(${heroImages[currentImageIndex]})`;
                heroElement.classList.add('fade-out');
                setTimeout(() => {
                    heroElement.classList.remove('fade-out');
                }, 500);
            }

            heroElement.style.backgroundImage = `url(${heroImages[currentImageIndex]})`;
            setInterval(changeBackgroundImage, intervalTime);
        });
    </script>
@endsection
