<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Voucher - Zizi Florist</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
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
            width: 15%;
            text-align: center;
        }

        .text-cell {
            width: 85%;
            text-align: center;
            padding-right: 15%;
        }

        .nama-toko {
            font-size: 24px;
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
        }

        .data-table th {
            background-color: #eee;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
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
                <img src="{{ asset('img/logo.jpg')}}" width="80" alt="Logo">
            </td>
            <td class="text-cell">
                <h1 class="nama-toko">ZIZI FLORIST</h1>
                <p style="margin:2px;">Dalam Kampus IPB, Dramaga, Kabupaten Bogor, Jawa Barat</p>
                <small>Laporan Data Kode Voucher & Diskon</small>
            </td>
        </tr>
    </table>
    <div class="garis-pemisah"></div>

    <div class="text-center" style="margin-bottom: 20px;">
        <h3 style="margin: 0; text-decoration: underline;">LAPORAN DATA VOUCHER</h3>
        <small>Periode Pembuatan: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }} s/d
            {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}</small>
    </div>

    {{-- TABEL DATA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal Dibuat</th>
                <th>Kode Voucher</th>
                <th width="20%">Nominal Potongan</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cetak as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>

                    {{-- Kode Voucher --}}
                    <td class="text-center fw-bold" style="font-size: 14px; letter-spacing: 1px;">
                        {{ $row->kode }}
                    </td>

                    {{-- Nominal (Cek apakah kolom kamu namanya 'jumlah' atau 'nominal') --}}
                    <td class="text-right">
                        @if ($row->tipe == 'percent')
                            {{-- TAMPILAN PERSEN (Contoh: 10%) --}}
                            {{ $row->nilai }}%
                        @else
                            {{-- TAMPILAN RUPIAH (Contoh: Rp 10.000) --}}
                            Rp {{ number_format($row->nilai ?? 0, 0, ',', '.') }}
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="text-center">
                        @if ($row->is_active == 1)
                            <span style="color: green; font-weight: bold;">Aktif</span>
                        @else
                            <span style="color: red; font-weight: bold;">Tidak Aktif</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">Data voucher tidak ditemukan.</td>
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
