@extends('frontend.v_layouts.app')

@section('content')
    <main class="desktop-content my-orders-page-content">
        <div class="container">
            <h1 class="page-title my-orders-title">Pesanan Saya</h1>

            <section class="orders-list-wrapper">

                {{-- Cek apakah user punya pesanan --}}
                @if ($orders->isEmpty())
                    <div class="cart-empty-state" style="text-align: center; margin-top: 50px;">
                        <i class="fa-solid fa-bag-shopping empty-icon"
                            style="font-size: 5rem; color: #ff96ac; margin-bottom: 20px;"></i>
                        <p class="empty-message">Belum ada pesanan, anda belum menambahkan pesanan apapun</p>
                        <a href="{{ route('frontend.beranda') }}#desktop-content" class="btn shop-now-btn"
                            style="background-color: #ff96ac; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; margin-top: 10px;">
                            Mulai Belanja
                        </a>
                    </div>
                @else
                    {{-- LOOPING DATA PESANAN (ORDER) --}}
                    @foreach ($orders as $order)
                        {{-- 1. BAGIAN HEADER STATUS --}}
                        <div class="order-status-header">
                            @php
                                // Logika mengubah status database menjadi teks bahasa Indonesia
                                $statusText = '';
                                switch ($order->status) {
                                    case 'Belum Dibayar':
                                        $statusText = 'Menunggu Pembayaran';
                                        break;
                                    case 'Menunggu Konfirmasi':
                                        $statusText = 'Menunggu Konfirmasi Admin';
                                        break;
                                    case 'Diproses':
                                        $statusText = 'Pesanan Sedang Dikemas';
                                        break;
                                    case 'Dikirim':
                                        $statusText = 'Pesanan Sedang Diantar';
                                        break;
                                    case 'Selesai':
                                        $statusText = 'Selesai';
                                        break;
                                    case 'Dibatalkan':
                                        $statusText = 'Pesanan Dibatalkan';
                                        break;
                                    case 'Pembayaran Ditolak':
                                        $statusText = 'Pembayaran Ditolak';
                                        break;
                                    default:
                                        $statusText = $order->status;
                                }
                            @endphp
                            <span class="status-text">{{ $statusText }}</span>

                            {{-- Opsional: Tampilkan Tanggal di sebelah status --}}
                            <small style="float: right; color: #888; font-size: 0.8rem;">
                                {{ $order->created_at->format('d M Y') }}
                            </small>
                        </div>

                        {{-- 2. KARTU PESANAN --}}
                        <div class="order-card">

                            {{-- GAMBAR PRODUK --}}
                            {{-- Kita ambil gambar dari item pertama sebagai cover --}}
                            @if ($order->detail->first())
                                <img src="{{ asset('storage/' . $order->detail->first()->produk->gambar) }}"
                                    alt="{{ $order->detail->first()->produk->nama_produk }}" class="order-item-image" />
                            @else
                                {{-- Gambar placeholder jika error --}}
                                <img src="img/default.png" class="order-item-image" />
                            @endif

                            <div class="order-details">

                                {{-- LOOPING ITEM (BARANG) DALAM SATU PESANAN --}}
                                {{-- Kita looping agar semua barang yang dibeli muncul namanya --}}
                                @foreach ($order->detail as $item)
                                    <div style="margin-bottom: 5px; border-bottom: 1px dashed #eee; padding-bottom: 5px;">
                                        <span class="order-item-name">{{ $item->produk->nama_produk }}</span>
                                        <span class="order-item-qty">x{{ $item->quantity }}</span>
                                        <span class="order-item-price">Rp.
                                            {{ number_format($item->harga, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach

                                {{-- RINGKASAN TOTAL --}}
                                <div class="order-summary-line">
                                    {{-- Menghitung total jumlah barang (sum quantity) --}}
                                    <span class="summary-label">Total {{ $order->detail->sum('quantity') }} Produk:</span>
                                    <span class="summary-value">Rp.
                                        {{ number_format($order->total_akhir, 0, ',', '.') }}</span>
                                </div>

                                {{-- TOMBOL AKSI --}}
                                <div class="order-actions">

                                    {{-- KONDISI 1: Belum Bayar (Bisa Bayar & Bisa Batal) --}}
                                    @if ($order->status == 'Belum Dibayar')
                                        {{-- Tombol Bayar --}}
                                        <a href="{{ route('payment.show', $order->id) }}" class="btn receive-btn"
                                            style="background-color: #ffc107; color: #000; margin-right: 5px;">
                                            Bayar Sekarang
                                        </a>

                                        {{-- Tombol Batalkan --}}
                                        <form id="form-batal-{{ $order->id }}"
                                            action="{{ route('order.cancel', $order->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf

                                            {{-- Perhatikan: type="button" dan onclick memanggil fungsi confirmCancel --}}
                                            <button type="button" class="btn"
                                                style="background-color: transparent; border: 1px solid #dc3545; color: #dc3545; padding: 8px 15px; border-radius: 20px;"
                                                onclick="confirmCancel({{ $order->id }})">
                                                Batalkan Pesanan
                                            </button>
                                        </form>

                                        {{-- KONDISI 2: Menunggu Konfirmasi (Masih Bisa Batal) --}}
                                    @elseif($order->status == 'Menunggu Konfirmasi')
                                        {{-- Tombol Batalkan --}}

                                        <form id="form-batal-{{ $order->id }}"
                                            action="{{ route('order.cancel', $order->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf

                                            {{-- Perhatikan: type="button" dan onclick memanggil fungsi confirmCancel --}}
                                            <button type="button" class="btn"
                                                style="background-color: transparent; border: 1px solid #dc3545; color: #dc3545; padding: 8px 15px; border-radius: 20px;"
                                                onclick="confirmCancel({{ $order->id }})">
                                                Batalkan Pesanan
                                            </button>
                                        </form>

                                        {{-- KONDISI 3: Sedang Dikirim (Tombol Terima Muncul) --}}
                                    @elseif($order->status == 'Dikirim')
                                        <form id="form-terima-{{ $order->id }}"
                                            action="{{ route('order.received', $order->id) }}" method="POST">
                                            @csrf

                                            {{-- Ubah type="button" dan panggil fungsi JS confirmReceive --}}
                                            <button type="button" class="btn receive-btn"
                                                onclick="confirmReceive('{{ $order->id }}')">
                                                Terima Pesanan
                                            </button>
                                        </form>

                                        {{-- KONDISI 4: Selesai --}}
                                    @elseif($order->status == 'Selesai')
                                        {{-- LOGIKA BARU: Cek apakah sudah dirating? --}}
                                        @if ($order->sudah_dirating)
                                            {{-- Jika SUDAH rating, tombol mati --}}
                                        @else
                                            {{-- Jika BELUM rating, tombol nyala --}}
                                            <button class="btn btn-outline btn-sm" style="margin-left: 5px;"
                                                onclick="openRatingModal({{ $order->id }})">
                                                <i class="fa-solid fa-star text-warning"></i> Nilai
                                            </button>
                                        @endif

                                        {{-- KONDISI 5: Dibatalkan --}}
                                    @elseif($order->status == 'Dibatalkan')
                                        {{-- KONDISI LAIN: Diproses --}}
                                    @else
                                    @endif

                                </div>
                            </div>
                        </div>
                        @if ($order->status == 'selesai')
                            <button class="btn btn-success btn-sm" disabled>Pesanan Selesai</button>

                            {{-- PANGGIL FUNGSI JS DAN KIRIM ID PESANAN --}}
                            <button class="btn btn-outline-primary btn-sm" onclick="openRatingModal({{ $order->id }})">
                                <i class="fa-solid fa-star text-warning"></i> Nilai
                            </button>
                        @endif
                    @endforeach
                @endif

            </section>
        </div>

        <div id="ratingModal" class="modal"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index: 1050;">
            <div class="modal-content rating-content bg-white p-4 m-auto mt-5 rounded"
                style="width: 90%; max-width: 500px; position: relative;">

                <h2 class="modal-title text-center mb-2">Beri Rating Produk</h2>
                <p class="modal-message text-center text-muted">Bagaimana kualitas produk yang Anda terima?</p>

                <input type="hidden" id="rating_order_id">

                <div class="star-rating text-center mb-3 fs-1" style="color: #e4e5e9; cursor: pointer;">
                    <i class="fa-solid fa-star star" data-value="1"></i>
                    <i class="fa-solid fa-star star" data-value="2"></i>
                    <i class="fa-solid fa-star star" data-value="3"></i>
                    <i class="fa-solid fa-star star" data-value="4"></i>
                    <i class="fa-solid fa-star star" data-value="5"></i>
                </div>

                <textarea id="rating_comment" class="form-control mb-3" rows="3" placeholder="Tuliskan ulasan Anda di sini..."></textarea>

                <div class="modal-actions d-flex justify-content-between">
                    <button class="btn btn-secondary" id="cancelRating">Nanti Saja</button>
                    <button class="btn btn-primary" id="submitRating">Kirim Rating</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        function confirmCancel(orderId) {
            Swal.fire({
                title: 'Yakin ingin membatalkan?',
                text: "Pesanan yang dibatalkan tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak, Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user klik "Ya", cari form berdasarkan ID lalu submit
                    document.getElementById('form-batal-' + orderId).submit();
                }
            })
        }

        function confirmReceive(orderId) {
            Swal.fire({
                title: 'Pesanan sudah diterima?',
                text: "Pastikan barang sudah sesuai. Aksi ini tidak dapat dibatalkan!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Warna Hijau
                cancelButtonColor: '#6c757d', // Warna Abu-abu
                confirmButtonText: 'Ya, Sudah Diterima',
                cancelButtonText: 'Cek Dulu'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form secara manual jika user klik Ya
                    document.getElementById('form-terima-' + orderId).submit();
                }
            })
        }

        document.addEventListener("DOMContentLoaded", function() {
            const ratingModal = document.getElementById("ratingModal");
            const stars = document.querySelectorAll(".star");
            const submitBtn = document.getElementById("submitRating");
            const cancelBtn = document.getElementById("cancelRating");
            const orderIdInput = document.getElementById("rating_order_id");
            const commentInput = document.getElementById("rating_comment");

            let selectedRating = 0;

            // --- 1. Fungsi Membuka Modal (Dipanggil dari tombol HTML) ---
            window.openRatingModal = function(orderId) {
                // Set ID pesanan ke input hidden
                orderIdInput.value = orderId;
                // Reset form
                selectedRating = 0;
                updateStars(0);
                commentInput.value = "";
                // Tampilkan modal
                ratingModal.style.display = "block";
            };

            // --- 2. Logika Klik Bintang (UI) ---
            stars.forEach((star) => {
                star.addEventListener("click", function() {
                    selectedRating = this.getAttribute("data-value");
                    updateStars(selectedRating);
                });

                // Efek Hover (Opsional, biar lebih interaktif)
                star.addEventListener("mouseover", function() {
                    updateStars(this.getAttribute("data-value"));
                });

                star.addEventListener("mouseleave", function() {
                    updateStars(selectedRating);
                });
            });

            function updateStars(value) {
                stars.forEach((s) => {
                    if (s.getAttribute("data-value") <= value) {
                        s.style.color = "#ffc107"; // Kuning
                    } else {
                        s.style.color = "#e4e5e9"; // Abu-abu
                    }
                });
            }

            // --- 3. Logika Kirim Rating ke Server (AJAX) ---
            submitBtn.addEventListener("click", function() {
                const orderId = orderIdInput.value;
                const comment = commentInput.value;

                if (selectedRating === 0) {
                    Swal.fire('Ups!', 'Silakan pilih bintang terlebih dahulu.', 'warning');
                    return;
                }

                // Tampilkan Loading
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                submitBtn.disabled = true;

                // Kirim data ke Laravel menggunakan Fetch
                fetch("{{ route('submit.rating') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}" // Wajib untuk Laravel
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            rating: selectedRating,
                            comment: comment
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Terima Kasih!', 'Ulasan Anda berhasil dikirim.', 'success');
                            closeModal();
                            // Opsional: Hilangkan tombol rating setelah sukses
                            // location.reload(); 
                        } else {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = 'Kirim Rating';
                        submitBtn.disabled = false;
                    });
            });

            // --- 4. Tutup Modal ---
            cancelBtn.addEventListener("click", closeModal);

            // Tutup jika klik di luar area modal
            window.onclick = function(event) {
                if (event.target == ratingModal) {
                    closeModal();
                }
            }

            function closeModal() {
                ratingModal.style.display = "none";
            }
        });
    </script>
    {{-- JANGAN LUPA SERTAKAN MODAL & SCRIPT RATING YANG TADI (Jika diperlukan) --}}
@endsection
