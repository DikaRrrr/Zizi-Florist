<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Zizi Florist</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            /* Font formal */
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        /* --- KOP SURAT --- */
        .kop-surat {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .kop-surat td {
            border: none;
            /* Hapus border tabel di kop */
            vertical-align: middle;
        }

        .logo-cell {
            width: 15%;
            /* Lebar kolom logo */
            text-align: center;
        }

        .text-cell {
            width: 85%;
            /* Lebar kolom teks */
            text-align: center;
            padding-right: 15%;
            /* Agar teks benar-benar di tengah halaman, bukan tengah kolom */
        }

        .nama-toko {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 2px;
        }

        .alamat-toko {
            font-size: 14px;
            margin: 2px 0;
        }

        .kontak-toko {
            font-size: 12px;
            font-style: italic;
        }

        /* GARIS GANDA (Ciri Khas Kop Surat) */
        .garis-pemisah {
            border-top: 3px solid black;
            border-bottom: 1px solid black;
            height: 2px;
            margin-bottom: 20px;
        }

        /* --- TABEL DATA --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            /* Data pakai font yang mudah dibaca */
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .data-table th {
            background-color: #eee;
            text-align: center;
            font-weight: bold;
        }

        /* Helpers */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        /* Tanda Tangan */
        .signature-container {
            width: 100%;
            margin-top: 40px;
        }

        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- 1. KOP SURAT (Layout Tabel) --}}
    <table class="kop-surat">
        <tr>
            {{-- KOLOM LOGO --}}
            <td class="logo-cell">
                {{-- Ganti src dengan link logo kamu atau asset('img/logo.png') --}}
                <img src="{{ asset('img/logo.jpg')}}" width="80" alt="Logo">
            </td>

            {{-- KOLOM TEKS --}}
            <td class="text-cell">
                <h1 class="nama-toko">ZIZI FLORIST</h1>
                <p class="alamat-toko">Dalam Kampus IPB, Dramaga, Kabupaten Bogor, Jawa Barat</p>
                <p class="kontak-toko">Telp: 0812-3456-7890 | Email: admin@ziziflorist.com</p>
            </td>
        </tr>
    </table>

    {{-- GARIS TEBAL TIPIS --}}
    <div class="garis-pemisah"></div>

    {{-- JUDUL LAPORAN --}}
    <div style="text-align: center; margin-bottom: 20px; font-family: Arial, sans-serif;">
        <h3 style="margin: 0; text-decoration: underline;">LAPORAN PENJUALAN PRODUK</h3>
        <small>Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }} s/d
            {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}</small>
    </div>

    {{-- 2. TABEL DATA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Produk</th>
                <th width="15%">Harga Satuan</th>
                <th width="10%">Stok</th>
                <th width="10%">Terjual</th>
                <th width="20%">Total Omset</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalOmset = 0;
                $totalTerjual = 0;
            @endphp

            @forelse ($cetak as $row)
                @php
                    $omset = $row->harga * $row->terjual;
                    $grandTotalOmset += $omset;
                    $totalTerjual += $row->terjual;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row->nama_produk }}</td>
                    <td class="text-right">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $row->stok }}</td>
                    <td class="text-center">{{ $row->terjual }}</td>
                    <td class="text-right fw-bold">Rp {{ number_format($omset, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">
                        <i>Data tidak ditemukan.</i>
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if (count($cetak) > 0)
            <tfoot>
                <tr style="background-color: #eee; font-weight: bold;">
                    <td colspan="4" class="text-right">TOTAL KESELURUHAN</td>
                    <td class="text-center">{{ $totalTerjual }}</td>
                    <td class="text-right">Rp {{ number_format($grandTotalOmset, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- 3. TANDA TANGAN --}}
    <div class="signature-container">
        <div class="signature-box">
            <p>Bogor, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <br><br><br>
            <p style="text-decoration: underline; font-weight: bold;">
                {{ Auth::user()->name ?? 'Administrator' }}
            </p>
            <p>Owner / Admin</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
