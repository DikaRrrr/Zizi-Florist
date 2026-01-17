<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan User - Zizi Florist</title>
    <style>
        /* Pakai Landscape agar kolom Email & Alamat muat */
        @page {
            size: landscape;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            padding: 20px;
        }

        /* Kop Surat */
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .kop-surat td {
            border: none;
            vertical-align: middle;
        }

        .logo-cell {
            width: 10%;
            text-align: center;
        }

        .text-cell {
            width: 90%;
            text-align: center;
            padding-right: 10%;
        }

        .nama-toko {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .garis-pemisah {
            border-top: 3px solid black;
            border-bottom: 1px solid black;
            height: 2px;
            margin-bottom: 20px;
        }

        /* Tabel Data */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .data-table th {
            background-color: #eee;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        /* Signature */
        .signature-container {
            margin-top: 30px;
            float: right;
            text-align: center;
            width: 200px;
        }
    </style>
</head>

<body>

    {{-- KOP SURAT --}}
    <table class="kop-surat">
        <tr>
            <td class="logo-cell">
                <img src="{{ asset('img/logo.jpg')}}" width="70" alt="Logo">
            </td>
            <td class="text-cell">
                <h1 class="nama-toko">ZIZI FLORIST</h1>
                <p style="margin:2px;">Dalam Kampus IPB, Dramaga, Kabupaten Bogor, Jawa Barat</p>
                <small>Laporan Data Pengguna Sistem (User & Admin)</small>
            </td>
        </tr>
    </table>
    <div class="garis-pemisah"></div>

    <div class="text-center" style="margin-bottom: 20px;">
        <h3 style="margin: 0; text-decoration: underline;">LAPORAN DATA PENGGUNA</h3>
        <small>Periode Pendaftaran: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }} s/d
            {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}</small>
    </div>

    {{-- TABEL DATA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tanggal Daftar</th>
                <th width="20%">Nama Lengkap</th>
                <th width="20%">Email</th>
                <th width="10%">Role</th>
                <th width="12%">No HP</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cetak as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>

                    {{-- Nama --}}
                    <td>{{ $row->nama }}</td>

                    {{-- Email --}}
                    <td>{{ $row->email }}</td>

                    {{-- Role (Kapitalisasi huruf awal) --}}
                    <td class="text-center">
                        @if ($row->role == 'admin')
                            <span style="font-weight:bold; color:blue;">ADMINISTRATOR</span>
                        @else
                            <span>Pelanggan</span>
                        @endif
                    </td>

                    {{-- HP --}}
                    <td class="text-center">{{ $row->hp ?? '-' }}</td>

                    {{-- Alamat --}}
                    <td>{{ $row->alamat ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Data pengguna tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="signature-container">
        <p>Bogor, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,</p>
        <br><br><br>
        <p class="fw-bold" style="text-decoration: underline;">{{ Auth::user()->name }}</p>
        <p>Administrator</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
