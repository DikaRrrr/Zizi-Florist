document.addEventListener("DOMContentLoaded", function () {
    const paymentOptions = document.querySelectorAll(".payment-option");
    paymentOptions.forEach((option) => {
        option.addEventListener("click", function () {
            const currentlySelected = document.querySelector(
                ".payment-option.selected"
            );
            if (currentlySelected) {
                currentlySelected.classList.remove("selected");
            }
            this.classList.add("selected");
        });
    });

    // FUNGSI 2: Menampilkan Modal Notifikasi Sukses
    const payNowButton = document.querySelector(".pay-now-btn");
    const successModal = document.getElementById("successModal");

    // Ketika tombol 'Pesan Sekarang' diklik
    payNowButton.addEventListener("click", function (event) {
        event.preventDefault(); // Mencegah form submit/navigasi

        // Tampilkan modal dengan mengubah properti display
        successModal.style.display = "block";

        // Opsional: Untuk navigasi ke index.html saat tombol 'Selesai' di modal diklik
        const modalCloseBtn = successModal.querySelector(".modal-close-btn");
        modalCloseBtn.addEventListener("click", function () {
            // Karena tombol ini adalah anchor <a>, navigasi ke index.html sudah otomatis
            // successModal.style.display = 'none'; // Jika Anda ingin tetap di halaman checkout
        });
    });

    // Fungsi untuk menutup modal jika mengklik di luar area konten (opsional)
    window.addEventListener("click", function (event) {
        if (event.target == successModal) {
            successModal.style.display = "none";
        }
    });
});
