<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan Lengkap</title>
    <style>
        /* Ubah Kertas Jadi Landscape (Memanjang) agar muat banyak kolom */
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
            padding: 5px;
            vertical-align: top;
        }

        .data-table th {
            background-color: #eee;
            font-weight: bold;
            text-align: center;
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

        .small-text {
            font-size: 10px;
            color: #555;
            display: block;
        }

        /* Status Colors */
        .status-ok {
            color: green;
            font-weight: bold;
        }

        .status-warn {
            color: orange;
            font-weight: bold;
        }

        .status-bad {
            color: red;
            font-weight: bold;
        }

        /* Tanda Tangan */
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
                <small>Laporan Detail Transaksi & Pengiriman</small>
            </td>
        </tr>
    </table>
    <div class="garis-pemisah"></div>

    <div class="text-center" style="margin-bottom: 15px;">
        <h3 style="margin: 0; text-decoration: underline;">LAPORAN PESANAN</h3>
        <small>Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }} s/d
            {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}</small>
    </div>

    {{-- TABEL DATA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="8%">Tanggal</th>
                <th width="22%">Data Penerima</th> {{-- Nama, HP, Alamat digabung --}}
                <th width="10%">Subtotal</th>
                <th width="8%">Ongkir</th>
                <th width="8%">Diskon</th>
                <th width="12%">Total Akhir</th>
                <th width="10%">Status</th>
                <th width="10%">Resi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotal = 0;
                $totalOngkir = 0;
            @endphp

            @forelse ($cetak as $row)
                @php
                    // Hitung total hanya jika status valid (bukan batal)
                    if (in_array($row->status, ['Dibayar', 'Dikirim', 'Selesai', 'Diproses'])) {
                        $grandTotal += $row->total_akhir;
                        $totalOngkir += $row->ongkir;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>

                    {{-- Tanggal --}}
                    <td class="text-center">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>

                    {{-- Data Penerima (Digabung agar hemat tempat) --}}
                    <td>
                        <strong>{{ $row->nama_penerima }}</strong> <br>
                        <span class="small-text">HP: {{ $row->hp_penerima }}</span>
                        <span class="small-text">{{ $row->alamat_penerima }}</span>
                    </td>

                    {{-- Rincian Keuangan --}}
                    <td class="text-right">Rp {{ number_format($row->subtotal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row->ongkir, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: red;">
                        {{ $row->jumlah_diskon > 0 ? '- Rp ' . number_format($row->jumlah_diskon, 0, ',', '.') : '-' }}
                    </td>

                    {{-- Total Akhir --}}
                    <td class="text-right fw-bold">Rp {{ number_format($row->total_akhir, 0, ',', '.') }}</td>

                    {{-- Status --}}
                    <td class="text-center">
                        @if ($row->status == 'Selesai')
                            <span class="status-ok">Selesai</span>
                        @elseif($row->status == 'Dibatalkan')
                            <span class="status-bad">Batal</span>
                        @else
                            <span class="status-warn">{{ $row->status }}</span>
                        @endif
                    </td>

                    {{-- Resi --}}
                    <td class="text-center">
                        {{ $row->resi ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL AKUMULASI</td>
                <td class="text-right">Rp {{ number_format($totalOngkir, 0, ',', '.') }}</td>
                <td></td> {{-- Kosong untuk kolom diskon --}}
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
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
