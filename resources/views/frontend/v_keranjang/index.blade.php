@extends('frontend.v_layouts.app')

@section('content')
    <main class="desktop-content cart-page-content">
        <h1 class="page-title">Keranjang Belanja</h1>

        {{-- LOGIKA: Cek apakah session cart ada isinya --}}
        @if (session('cart') && count(session('cart')) > 0)
            <div class="cart-container">
                <div class="cart-items-list">

                    {{-- Variable awal --}}
                    @php $totalBelanja = 0; @endphp

                    {{-- LOOPING DATA KERANJANG --}}
                    @foreach (session('cart') as $id => $details)
                        {{-- ========================================== --}}
                        {{-- PERBAIKAN: LOGIKA HITUNG HARGA ADA DISINI --}}
                        {{-- ========================================== --}}
                        @php
                            $subtotalItem = $details['price'] * $details['quantity'];
                            $totalBelanja += $subtotalItem;
                        @endphp
                        {{-- ========================================== --}}

                        <div class="cart-item" data-id="{{ $id }}" style="position: relative; padding-right: 30px;">

                            {{-- TOMBOL HAPUS --}}
                            <form action="{{ route('keranjang.hapus', $id) }}" method="POST"
                                style="position: absolute; top: 15px; right: 10px; z-index: 100;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete-theme" title="Hapus Item"
                                    style="background:none; border:none; color:#6c4a3a; font-size:1.2rem; cursor:pointer;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                            {{-- GAMBAR --}}
                            @if ($details['foto'])
                                <img src="{{ asset('storage/' . $details['foto']) }}" alt="{{ $details['name'] }}"
                                    class="cart-item-image" />
                            @else
                                <img src="https://placehold.co/100x100?text=No+Img" class="cart-item-image" />
                            @endif

                            {{-- DETAIL ITEM --}}
                            <div class="item-details">
                                <span class="item-name">{{ $details['name'] }}</span>
                                <span class="item-price">Rp. {{ number_format($details['price'], 0, ',', '.') }}</span>
                            </div>

                            {{-- QTY CONTROL --}}
                            <div class="item-quantity-control">
                                {{-- TOMBOL KURANG --}}
                                {{-- WAJIB ADA: data-id="{{ $id }}" --}}
                                <button class="qty-btn minus-btn change-qty" data-action="minus"
                                    data-id="{{ $id }}">
                                    <i class="fa-solid fa-minus"></i>
                                </button>

                                {{-- ANGKA QUANTITY --}}
                                <span class="item-qty-value">{{ $details['quantity'] }}</span>

                                {{-- TOMBOL TAMBAH --}}
                                {{-- WAJIB ADA: data-id="{{ $id }}" --}}
                                <button class="qty-btn plus-btn change-qty" data-action="plus"
                                    data-id="{{ $id }}">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- BOX RINGKASAN --}}
                <div class="cart-summary-box">
                    <h3 class="summary-title">Ringkasan Belanja</h3>

                    {{-- BAGIAN INPUT VOUCHER --}}
                    <div class="voucher-section">
                        <p class="voucher-label"><i class="fa-solid fa-ticket"></i> Punya Kode Voucher?</p>
                        <div class="voucher-input-group">
                            <input type="text" id="voucherCode" placeholder="Masukkan kode promo" />
                            <button class="btn-apply-voucher" id="applyVoucher">Gunakan</button>
                        </div>
                        <p id="voucherMessage" class="voucher-status-msg mt-2"></p>
                    </div>

                    {{-- SUBTOTAL --}}
                    <div class="summary-line">
                        <span>Subtotal ({{ count(session('cart')) }} Item)</span>
                        {{-- Pastikan variabel $totalBelanja dikirim dari controller --}}
                        <span id="subtotalDisplay">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</span>
                    </div>

                    {{-- ONGKIR (Flat Rate 20rb) --}}
                    <div class="summary-line">
                        <span>Ongkos Kirim</span>
                        <span>Rp 20.000</span>
                    </div>

                    {{-- DISKON (Awalnya disembunyikan style="display:none") --}}
                    <div class="summary-line discount-line text-danger" id="discountRow" style="display: none;">
                        <span>Diskon Voucher</span>
                        <span id="discountAmount">- Rp 0</span>
                    </div>

                    <hr>

                    {{-- TOTAL --}}
                    <div class="summary-line total-line">
                        <span>Total</span>
                        {{-- Beri class 'final-total' untuk target JS --}}
                        <span class="final-total fw-bold text-success">
                            Rp {{ number_format($totalBelanja + 20000, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="mt-4">
                        {{-- Arahkan ke route halaman checkout --}}
                        <a href="{{ route('checkout') }}" class="btn w-100 py-2 fw-bold">
                            Lanjut ke Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- STATE KOSONG --}}
            <div class="cart-empty-state" style="text-align: center; margin-top: 50px;">
                <i class="fa-solid fa-bag-shopping empty-icon"
                    style="font-size: 5rem; color: #ff96ac; margin-bottom: 20px;"></i>
                <p class="empty-message">Keranjang kosong, anda belum menambahkan produk apapun</p>
                <a href="{{ route('frontend.beranda') }}#desktop-content" class="btn shop-now-btn"
                    style="background-color: #ff96ac; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; margin-top: 10px;">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </main>

    {{-- JQUERY (Wajib ada untuk script di bawah) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script type="text/javascript">
        // Saat tombol dengan class .change-qty diklik
        $(".change-qty").click(function(e) {
            e.preventDefault();

            var ele = $(this);
            // Ambil ID produk dari parent .cart-item
            var id = ele.closest(".cart-item").attr("data-id");

            // Ambil jumlah saat ini dari teks angka
            var currentQty = parseInt(ele.parent().find(".item-qty-value").text());

            // Cek tombol mana yang diklik (Plus atau Minus)
            var action = ele.attr("data-action");
            var newQty;

            if (action === "plus") {
                newQty = currentQty + 1;
            } else {
                newQty = currentQty - 1;
            }

            // Validasi: Tidak boleh kurang dari 1
            if (newQty < 1) {
                return; // Stop, jangan lakukan apa-apa
            }

            // Kirim Request ke Server (AJAX)
            $.ajax({
                url: '{{ route('keranjang.update') }}', // Panggil route update
                method: "patch",
                data: {
                    _token: '{{ csrf_token() }}', // Token keamanan Laravel
                    id: id,
                    quantity: newQty
                },
                success: function(response) {
                    // Jika sukses, refresh halaman agar Total Harga terhitung ulang
                    window.location.reload();
                }
            });
        });

        $(document).ready(function() {
            $('#applyVoucher').click(function(e) {
                e.preventDefault();

                // Variable sesuai kode lama kamu
                let kode = $('#voucherCode').val();

                // Pastikan Controller mengirim variabel bernama $totalBelanja
                // Jika error "Undefined variable", berarti controller kamu kirimnya $subtotal
                let subtotal = {{ $totalBelanja ?? 0 }};
                let ongkir = {{ $ongkir ?? 20000 }};

                if (kode == "") {
                    alert("Harap isi kode voucher!");
                    return;
                }

                $.ajax({
                    url: "{{ route('checkout.apply_voucher') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_voucher: kode,
                        subtotal: subtotal
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            // 1. Tampilkan Pesan Sukses
                            $('#voucherMessage').html('<small class="text-success fw-bold">' +
                                response.message + '</small>');

                            // 2. Munculkan Baris Diskon
                            // PERBAIKAN KECIL: Gunakan css display flex agar rapi dengan class d-flex HTML kamu
                            $('#discountRow').css('display', 'flex');

                            // 3. Ambil Nilai Potongan (Sesuai JSON debugging kamu: nilai_potongan)
                            let potongan = parseInt(response.nilai_potongan);

                            // Update Teks Diskon
                            $('#discountAmount').text('- Rp ' + new Intl.NumberFormat('id-ID')
                                .format(potongan));

                            // 4. Hitung Total Baru
                            let totalBaru = parseInt(subtotal) + parseInt(ongkir) - potongan;

                            // 5. Update Angka Total Bayar
                            $('.final-total').text('Rp ' + new Intl.NumberFormat('id-ID')
                                .format(totalBaru));

                            // 6. Matikan tombol
                            $('#applyVoucher').prop('disabled', true).text('Terpakai');
                            $('#voucherCode').prop('disabled', true);

                        } else {
                            // Tampilkan Pesan Error
                            $('#voucherMessage').html('<small class="text-danger">' + response
                                .message + '</small>');
                        }
                    },
                    error: function(xhr) {
                        alert("Terjadi kesalahan sistem");
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
