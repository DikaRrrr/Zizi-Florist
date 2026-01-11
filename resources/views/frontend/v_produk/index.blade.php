@extends('frontend.v_layouts.app')

@section('content')
    <div class="container mt-5 mb-5">
        <div class="detail-container">
            {{-- BAGIAN ATAS: FOTO & INFO PRODUK --}}
            <div class="row">
                {{-- Kolom Kiri: Gambar --}}
                <div class="col-md-5 mb-4">
                    <div class="detail-image-box text-center">
                        @if ($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" class="img-fluid rounded shadow-sm"
                                alt="{{ $product->nama_produk }}" style="max-height: 400px;" />
                        @else
                            <img src="https://placehold.co/400x400?text=No+Image" class="img-fluid rounded" alt="No Image" />
                        @endif
                    </div>
                </div>

                {{-- Kolom Kanan: Info --}}
                <div class="col-md-7">
                    <div class="detail-info">
                        <h1 class="detail-title fw-bold">{{ $product->nama_produk }}</h1>

                        {{-- Deskripsi --}}
                        <div class="detail-desc text-muted mb-3">
                            {!! $product->deskripsi !!}
                        </div>

                        {{-- Harga & Rating Singkat --}}
                        <div class="detail-rating mb-4">
                            <h3 class="detail-price fw-bold">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>

                            <div class="d-flex align-items-center mt-2">
                                <span class="badge bg-warning text-dark me-2">
                                    <i class="fa-solid fa-star"></i> {{ number_format($avgRating, 1) }}
                                </span>
                                <span class="text-muted small">• {{ $totalReviews }} Ulasan • {{ $product->terjual ?? 0 }}
                                    Terjual</span>
                            </div>
                        </div>

                        {{-- Form Tambah ke Keranjang --}}
                        @if ($product->stok > 0)
                            {{-- JIKA STOK ADA --}}
                            <form action="{{ route('keranjang.tambah', $product->id) }}" method="POST">
                                @csrf
                                <div class="d-flex align-items-center gap-3">
                                    {{-- Pastikan ada class warna tombol (misal: btn-primary atau custom class kamu) --}}
                                    <button type="submit" class="btn px-4 py-2">
                                        <i class="fa-solid fa-cart-shopping me-2"></i> Masukkan Keranjang
                                    </button>
                                </div>
                            </form>
                        @else
                            {{-- JIKA STOK HABIS --}}
                            <div class="d-flex align-items-center gap-3">
                                {{-- Gunakan btn-secondary (abu-abu) dan atribut 'disabled' --}}
                                <button type="button" class="btn btn-secondary px-4 py-2" disabled>
                                    <i class="fa-solid fa-circle-xmark me-2"></i> Stok Habis
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN BAWAH: REVIEW LIST --}}
        <section class="reviews-section mt-5 pt-4 border-top">
            <div class="reviews-header mb-4">
                <h3 class="section-title">Ulasan Produk</h3>

                {{-- Tampilan Rata-rata Besar --}}
                <div class="d-flex align-items-center gap-3">
                    <h1 class="fw-bold mb-0 text-warning">{{ number_format($avgRating, 1) }}</h1>
                    <div>
                        <div class="stars text-warning">
                            {{-- Logic Bintang Rata-rata (Full Star) --}}
                            @php $roundedRating = round($avgRating); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="fa-solid fa-star {{ $i <= $roundedRating ? '' : 'text-secondary opacity-25' }}"></i>
                            @endfor
                        </div>
                        <span class="text-muted small">Berdasarkan {{ $totalReviews }} ulasan</span>
                    </div>
                </div>
            </div>

            {{-- LIST ULASAN USER --}}
            <div class="reviews-list">

                {{-- 1. LOOPING DATA DARI DATABASE --}}
                @forelse($product->rating as $review)
                    <div class="review-card">
                        <div class="review-user-info">

                            {{-- AVATAR: Huruf depan nama user --}}
                            <div class="user-avatar">
                                {{-- Cek apakah user punya avatar --}}
                                @if (!empty($review->user->avatar))
                                    {{-- PERHATIKAN BAGIAN INI: --}}
                                    {{-- Kita tambahkan 'storage/avatars/' secara manual --}}
                                    <img src="{{ asset('storage/' . $review->user->avatar) }}"
                                        alt="{{ $review->user->name }}" class="avatar-img" {{-- Script anti-broken image: Jika gambar gagal dimuat, sembunyikan gambar & tampilkan inisial --}}
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    {{-- Fallback Inisial (Hidden by default) --}}
                                    <div
                                        style="display:none; width:100%; height:100%; align-items:center; justify-content:center;">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                @else
                                    {{-- Jika tidak punya avatar, tampilkan inisial --}}
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                @endif
                            </div>

                            <div>
                                {{-- NAMA USER --}}
                                <span class="user-name">{{ $review->user->nama }}</span>

                                {{-- BINTANG RATING --}}
                                <div class="user-rating-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            {{-- Bintang Kuning (Aktif) --}}
                                            <i class="fa-solid fa-star" style="color: #ffd43b;"></i>
                                        @else
                                            {{-- Bintang Abu-abu (Kosong) --}}
                                            <i class="fa-solid fa-star" style="color: #e4e5e9;"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                            {{-- TANGGAL REVIEW --}}
                            <span class="review-date ms-auto">
                                {{ $review->created_at->format('d M Y') }}
                            </span>
                        </div>

                        {{-- ISI KOMENTAR --}}
                        <p class="review-text">
                            {{ $review->komentar ?? 'User memberikan rating tanpa komentar tertulis.' }}
                        </p>
                    </div>

                @empty
                    {{-- TAMPILAN JIKA DATA KOSONG --}}
                    <div class="review-card text-center py-4">
                        <p class="review-text text-muted">
                            Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!
                        </p>
                    </div>
                @endforelse

            </div>
        </section>
    </div>
@endsection
