@extends('frontend.v_layouts.app') @section('content')
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
        <section class="welcome-bar">
            <div class="welcome-text">
                <h1>Selamat Datang di ZIZI FLORIST</h1>
            </div>

            {{-- GANTI DIV LAMA DENGAN FORM INI --}}
            {{-- Perhatikan bagian action --}}
            <form action="{{ route('produk.search') }}#hasil-pencarian" method="GET"
                class="search-bar-container welcome-search">

                <input type="text" name="keyword" placeholder="Cari bunga..." class="search-input"
                    value="{{ request('keyword') }}" />

                <button type="submit" class="search-button">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </section>

        <section class="bestseller-section">
            <h2 class="section-title">Best Seller 🔥</h2>

            <div class="bestseller-carousel">
                {{-- PERBAIKAN: Ganti 'as $produk' menjadi 'as $item' --}}
                {{-- Agar tidak bentrok dengan variabel $produk utama di bawah --}}
                @foreach ($best_sellers as $item)
                    <div class="bestseller-item">

                        {{-- Gunakan $item->slug, bukan $produk->slug --}}
                        <a href="{{ route('produk.detail', $item->slug) }}" style="text-decoration: none; color: inherit;">

                            {{-- GAMBAR --}}
                            @if ($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}"
                                    class="bestseller-image" />
                            @else
                                <img src="https://placehold.co/300x300?text=No+Image" alt="No Image"
                                    class="bestseller-image" />
                            @endif

                            <div class="bestseller-details">
                                {{-- NAMA --}}
                                <span class="product-name">{{ $item->nama_produk }}</span>

                                {{-- DETAIL LAIN --}}
                                <small style="display:block; font-size: 0.8rem; color: #888;">
                                    Terjual: {{ $item->terjual }}
                                </small>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="product-list-section" id="hasil-pencarian">
            <div class="product-grid">

                {{-- MULAI LOOPING DATA DARI DATABASE --}}
                @foreach ($produk as $row)
                    {{-- Link menuju detail (Untuk sementara hash # dulu, nanti bisa diganti route detail) --}}
                    <a href="{{ route('produk.detail', $row->id) }}">
                        <div class="product-card">

                            {{-- 1. FOTO PRODUK --}}
                            {{-- Cek apakah foto ada di database & file fisik ada --}}
                            @if ($row->foto)
                                <img src="{{ asset('storage/' . $row->foto) }}" alt="{{ $row->nama_produk }}"
                                    class="product-image">
                            @else
                                {{-- Gambar default jika tidak ada foto --}}
                                <img src="https://placehold.co/300x300?text=No+Image" alt="No Image" class="product-image">
                            @endif

                            <div class="product-info">

                                <div class="product-header-line">
                                    {{-- 2. NAMA PRODUK --}}
                                    <span class="product-name">{{ $row->nama_produk }}</span>

                                    {{-- Rating (Sementara Statis karena belum ada kolom rating di DB) --}}
                                    <span class="rating">
                                        <i class="fa-solid fa-star" style="color: #FFD43B;"></i> 5
                                    </span>
                                </div>

                                <div class="price-sold-line">
                                    {{-- 3. HARGA (Format Rupiah) --}}
                                    <span class="price">Rp. {{ number_format($row->harga, 0, ',', '.') }}</span>

                                    {{-- 4. JUMLAH TERJUAL --}}
                                    <span class="sold-count">{{ $row->terjual }} Sold</span>
                                </div>

                            </div>
                        </div>
                    </a>
                @endforeach
                {{-- SELESAI LOOPING --}}

                {{-- Jika tidak ada produk sama sekali --}}
                @if ($produk->isEmpty())
                    <div class="cart-empty-state" style="text-align: center; margin-top: auto;">
                        <i class="fa-solid fa-box-open empty-icon"
                            style="font-size: 5rem; color: #ff96ac; margin-bottom: 20px;"></i>
                        <p class="empty-message">Tidak ada produk.</p>
                    </div>
                @endif

            </div>
            <section class="product-list-section">
                <div class="product-grid">

                    {{-- LOOPING PRODUK --}}
                    @foreach ($produk as $row)
                        {{-- ... kode kartu produk kamu ... --}}
                    @endforeach

                </div> {{-- Penutup Grid --}}

                {{-- TAMBAHKAN INI: TOMBOL PINDAH HALAMAN --}}
                <div class="pagination-container mt-4">
                    {{ $produk->appends(['keyword' => request('keyword')])->fragment('hasil-pencarian')->links('pagination::bootstrap-5') }}
                </div>

            </section>
        </section>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Definisikan daftar gambar latar belakang
            const heroImages = [
                "{{ asset('img/hero-7.jpg') }}", // Hapus spasi di akhir sebelum tanda kutip tutup
                "{{ asset('img/hero-1.jpg') }}",
                "{{ asset('img/hero-2.jpg') }}",
            ];

            const heroElement = document.querySelector(".hero");
            let currentImageIndex = 0;
            const intervalTime = 5000; // 10000 milidetik = 10 detik

            // 2. Fungsi untuk mengganti gambar
            function changeBackgroundImage() {
                // Pindah ke indeks berikutnya
                currentImageIndex = (currentImageIndex + 1) % heroImages.length;

                // Atur gambar latar belakang
                heroElement.style.backgroundImage = `url(${heroImages[currentImageIndex]})`;

                // Opsional: Tambahkan kelas untuk transisi yang mulus (jika transisi CSS ditambahkan)
                heroElement.classList.add('fade-out');
                setTimeout(() => {
                    heroElement.classList.remove('fade-out');
                }, 500); // Sesuaikan dengan durasi transisi
            }

            // 3. Atur gambar awal
            heroElement.style.backgroundImage = `url(${heroImages[currentImageIndex]})`;

            // 4. Atur interval untuk memanggil fungsi secara berkala
            setInterval(changeBackgroundImage, intervalTime);
        });
    </script>
@endsection
