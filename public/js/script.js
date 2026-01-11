document.addEventListener("DOMContentLoaded", function () {
    // 1. Definisikan daftar gambar latar belakang
    const heroImages = [
        "{{ asset('img/hero-7.jpg')}} ", // Gambar yang sudah ada
        "{{ asset('img/hero-1.jpg')}} ", // Ganti dengan path gambar Anda yang lain
        "{{ asset('img/hero-2.jpg')}} ", // Ganti dengan path gambar Anda yang lain
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
        // heroElement.classList.add('fade-out');
        // setTimeout(() => {
        //     heroElement.classList.remove('fade-out');
        // }, 500); // Sesuaikan dengan durasi transisi
    }

    // 3. Atur gambar awal
    heroElement.style.backgroundImage = `url(${heroImages[currentImageIndex]})`;

    // 4. Atur interval untuk memanggil fungsi secara berkala
    setInterval(changeBackgroundImage, intervalTime);
});

document.addEventListener("DOMContentLoaded", function () {
    // Ambil elemen carousel
    const carousel = document.querySelector(".bestseller-carousel");

    // Periksa apakah elemen carousel ditemukan
    if (!carousel) {
        console.error("Elemen .bestseller-carousel tidak ditemukan.");
        return;
    }

    // Kecepatan geser (dalam milidetik). Contoh: 3000ms = 3 detik
    const scrollInterval = 3000;

    // Variabel untuk menyimpan timer interval
    let autoScroll;

    // Fungsi untuk menggeser carousel ke kanan
    function slideNext() {
        // Ambil item pertama untuk mendapatkan lebarnya.
        const firstItem = carousel.querySelector(".bestseller-item");
        if (!firstItem) return;

        // Lebar item pertama (termasuk gap/margin 20px dari CSS)
        const itemWidth = firstItem.offsetWidth + 20;

        // Geser ke posisi saat ini + lebar satu item
        carousel.scrollLeft += itemWidth;

        // Logika untuk kembali ke awal (looping)
        // scrollWidth: total lebar konten (tersembunyi + terlihat)
        // clientWidth: lebar area yang terlihat
        // Kita bandingkan dengan scrollWidth dikurangi clientWidth (posisi scroll maksimal)
        const maxScroll = carousel.scrollWidth - carousel.clientWidth;

        if (carousel.scrollLeft >= maxScroll - 5) {
            // -5 untuk toleransi
            // Kembali ke awal
            carousel.scrollLeft = 0;
        }
    }

    // Fungsi untuk memulai auto-scroll
    function startAutoScroll() {
        // Pastikan tidak ada interval yang berjalan ganda sebelum memulai yang baru
        stopAutoScroll();
        autoScroll = setInterval(slideNext, scrollInterval);
    }

    // Fungsi untuk menghentikan auto-scroll
    function stopAutoScroll() {
        clearInterval(autoScroll);
    }

    // Panggil fungsi untuk memulai auto-scroll saat halaman dimuat
    startAutoScroll();

    // Hentikan geser saat pengguna mengarahkan mouse (hover) ke atas carousel
    carousel.addEventListener("mouseenter", stopAutoScroll);

    // Lanjutkan geser saat mouse meninggalkan carousel
    carousel.addEventListener("mouseleave", startAutoScroll);
});

document.addEventListener("DOMContentLoaded", function () {
    const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

    dropdownToggles.forEach((toggle) => {
        toggle.addEventListener("click", function () {
            // Dapatkan ID target dari atribut data-target
            const targetId = this.getAttribute("data-target");
            const targetContent = document.getElementById(targetId);

            // Periksa apakah konten sudah terlihat
            const isVisible = targetContent.classList.contains("show");

            // --- Logika Tutup Semua ---
            // Tutup semua dropdown yang mungkin terbuka dan hapus kelas 'active'
            document
                .querySelectorAll(".dropdown-content")
                .forEach((content) => {
                    content.classList.remove("show");
                });
            document.querySelectorAll(".dropdown-toggle").forEach((item) => {
                item.classList.remove("active");
            });

            // --- Logika Buka Target ---
            // Jika konten sebelumnya tersembunyi, tampilkan sekarang
            if (!isVisible) {
                targetContent.classList.add("show");
                this.classList.add("active");
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // 1. Ambil semua elemen opsi pembayaran
    const paymentOptions = document.querySelectorAll(".payment-option");

    // 2. Loop melalui setiap opsi dan tambahkan event listener
    paymentOptions.forEach((option) => {
        option.addEventListener("click", function () {
            // 3. Cari dan hapus kelas 'selected' dari opsi yang saat ini terpilih
            const currentlySelected = document.querySelector(
                ".payment-option.selected"
            );
            if (currentlySelected) {
                currentlySelected.classList.remove("selected");
            }

            // 4. Tambahkan kelas 'selected' ke opsi yang baru saja diklik
            this.classList.add("selected");
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const applyBtn = document.getElementById("applyVoucher");
    const voucherInput = document.getElementById("voucherCode");
    const voucherMsg = document.getElementById("voucherMessage");
    const discountRow = document.getElementById("discountRow");
    const discountText = document.getElementById("discountAmount");
    const totalPriceText = document.getElementById("totalPrice");

    const ORIGINAL_PRICE = 1250000;

    applyBtn.addEventListener("click", function () {
        const code = voucherInput.value.trim().toUpperCase();

        if (code === "ZIZIFLORIST") {
            const discount = 50000;
            const newTotal = ORIGINAL_PRICE - discount;

            // Beri feedback sukses
            voucherMsg.textContent =
                "Voucher berhasil digunakan! Potongan Rp. 50.000";
            voucherMsg.className = "voucher-status-msg success";

            // Tampilkan baris diskon di ringkasan
            discountRow.style.display = "flex";
            discountText.textContent = "- Rp. 50.000";

            // Update total
            totalPriceText.textContent =
                "Rp. " + newTotal.toLocaleString("id-ID");
        } else if (code === "") {
            voucherMsg.textContent = "Silakan masukkan kode voucher.";
            voucherMsg.className = "voucher-status-msg error";
        } else {
            // Beri feedback gagal
            voucherMsg.textContent = "Maaf, kode voucher tidak valid.";
            voucherMsg.className = "voucher-status-msg error";

            // Sembunyikan diskon jika sebelumnya ada
            discountRow.style.display = "none";
            totalPriceText.textContent =
                "Rp. " + ORIGINAL_PRICE.toLocaleString("id-ID");
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("mobile-menu");
    const navLinks = document.querySelector(".nav-links");

    // Toggle menu saat ikon hamburger diklik
    menuToggle.addEventListener("click", function () {
        navLinks.classList.toggle("active");

        // Animasi icon (opsional: ganti jadi 'X' saat buka)
        const icon = this.querySelector("i");
        if (navLinks.classList.contains("active")) {
            icon.classList.replace("fa-bars", "fa-xmark");
        } else {
            icon.classList.replace("fa-xmark", "fa-bars");
        }
    });

    // Tutup menu otomatis jika user klik link
    document.querySelectorAll(".nav-links a").forEach((link) => {
        link.addEventListener("click", () => {
            navLinks.classList.remove("active");
            menuToggle
                .querySelector("i")
                .classList.replace("fa-xmark", "fa-bars");
        });
    });
});
