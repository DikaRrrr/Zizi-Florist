<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. AKUN ADMIN (Pemilik Toko)
        User::create([
            'nama' => 'Owner Zizi',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // Passwordnya: password
            'role' => 'admin',
            'hp' => '081234567890',
            'alamat' => 'Kantor Pusat Zizi Florist, Bogor',
        ]);

        // 2. AKUN CUSTOMER (Pembeli Biasa)
        User::create([
            'nama' => 'Customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('customer123'), // Passwordnya: password
            'role' => 'customer',
            'hp' => '089876543210',
            'alamat' => 'Jl. Mawar No. 10, Bogor',
        ]);



        Schema::disableForeignKeyConstraints();

        // 2. KOSONGKAN TABEL DULU (Agar tidak error Duplicate Entry)
        DB::table('voucher')->truncate();

        // 3. Nyalakan lagi cek Foreign Key
        Schema::enableForeignKeyConstraints();

        $vouchers = [
            // 1. Voucher Potongan Tetap (Rp 10.000)
            // Skenario: Belanja minimal 50rb, dapet diskon 10rb
            [
                'kode' => 'ZIZIHEMAT',
                'tipe' => 'fixed', // Sesuai enum migrasi
                'nilai' => 10000,
                'minimal_pembelian' => 50000,
                'tanggal_mulai' => Carbon::now()->subDays(1)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addMonths(1)->toDateString(),
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2. Voucher Persen (Diskon 50%)
            // Skenario: Diskon setengah harga tanpa minimal pembelian
            [
                'kode' => 'DISKON50',
                'tipe' => 'percent', // Sesuai enum migrasi
                'nilai' => 50, // Artinya 50%
                'minimal_pembelian' => 0,
                'tanggal_mulai' => Carbon::now()->subDays(1)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addMonths(1)->toDateString(),
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 3. Voucher Sultan (Min Belanja Tinggi)
            // Skenario: Tes validasi minimal pembelian (Harus belanja 1 juta)
            [
                'kode' => 'SULTAN100',
                'tipe' => 'fixed',
                'nilai' => 100000, // Potongan 100rb
                'minimal_pembelian' => 1000000, // Min belanja 1 Juta
                'tanggal_mulai' => Carbon::now()->subDays(1)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addMonths(3)->toDateString(),
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 4. Voucher Kadaluarsa (Sudah Lewat)
            // Skenario: Tes validasi tanggal (Harus Gagal)
            [
                'kode' => 'KADALUARSA',
                'tipe' => 'fixed',
                'nilai' => 20000,
                'minimal_pembelian' => 0,
                'tanggal_mulai' => Carbon::now()->subMonths(2)->toDateString(),
                'tanggal_selesai' => Carbon::now()->subDays(1)->toDateString(), // Kemarin sudah habis
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 5. Voucher Non-Aktif
            // Skenario: Tes validasi is_active (Harus Gagal)
            [
                'kode' => 'OFFLIMIT',
                'tipe' => 'percent',
                'nilai' => 10,
                'minimal_pembelian' => 0,
                'tanggal_mulai' => Carbon::now()->subDays(1)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addMonths(1)->toDateString(),
                'is_active' => false, // Dimatikan admin
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('voucher')->insert($vouchers);
    }
}
