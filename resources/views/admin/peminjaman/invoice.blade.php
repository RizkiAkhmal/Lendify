<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Peminjaman #{{ $peminjaman->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; margin: 0; padding: 20px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 14px; line-height: 20px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #009ef7; }
        .info-grid { display: grid; grid-cols: 1 md:grid-cols-2 gap-10; margin-bottom: 30px; }
        .section-title { font-weight: bold; text-transform: uppercase; background: #f4f4f4; padding: 5px 10px; margin-bottom: 10px; border-left: 4px solid #009ef7; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { padding: 12px; border: 1px solid #eee; text-align: left; }
        table th { background: #f9fafb; font-weight: bold; }
        .total-box { text-align: right; margin-top: 20px; }
        .total-row { display: flex; justify-content: flex-end; gap: 50px; font-weight: bold; font-size: 16px; padding: 10px 0; border-top: 2px solid #333; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        .signature-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 50px; margin-top: 60px; text-align: center; }
        .signature-box { height: 100px; border-bottom: 1px solid #333; margin-bottom: 10px; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .invoice-box { border: none; box-shadow: none; }
        }
        
        .btn-print { background: #009ef7; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; margin-bottom: 20px; }
        .btn-back { background: #eee; color: #333; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>



    <div class="invoice-box">
        <div class="header">
            <div>
                <h1>LENDIFY</h1>
                <p>Sistem Peminjaman Alat Praktik<br>Email: support@lendify</p>
            </div>
            <div style="text-align: right;">
                <h2 style="margin: 0;">INVOICE</h2>
                <p>#INV-{{ date('Ymd') }}-{{ $peminjaman->id }}<br>Tanggal: {{ date('d/m/Y') }}</p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 40px;">
            <div style="width: 48%;">
                <div class="section-title">Informasi Peminjam</div>
                <p><strong>Nama:</strong> {{ $peminjaman->user->name }}</p>
                <p><strong>ID Pengguna:</strong> #{{ $peminjaman->user->id }}</p>
                <p><strong>Email:</strong> {{ $peminjaman->user->email }}</p>
                <p><strong>Telepon:</strong> {{ $peminjaman->user->phone ?? '-' }}</p>
            </div>
            <div style="width: 48%;">
                <div class="section-title">Status Transaksi</div>
                <p><strong>Status:</strong> <span style="text-transform: uppercase; font-weight: bold;">{{ $peminjaman->status }}</span></p>
                <p><strong>Keperluan:</strong> {{ $peminjaman->keperluan }}</p>
            </div>
        </div>

        <div class="section-title">Detail Barang & Waktu</div>
        <table>
            <thead>
                <tr>
                    <th>Deskripsi Alat</th>
                    <th>Jumlah</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali (Rencana)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $peminjaman->alat->nama_alat }}</strong><br>
                        <small>{{ $peminjaman->alat->kode_alat }} | {{ $peminjaman->alat->merk ?? 'Tanpa Merk' }}</small>
                    </td>
                    <td>{{ $peminjaman->jumlah }} Unit</td>
                    <td>{{ $peminjaman->tanggal_peminjaman ? \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $peminjaman->tanggal_kembali_rencana ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y') : '-' }}</td>
                </tr>
            </tbody>
        </table>

        @if($peminjaman->pengembalian)
            <div class="section-title">Informasi Pengembalian</div>
            <table>
                <thead>
                    <tr>
                        <th>Tgl Kembali Aktual</th>
                        <th>Kondisi Akhir</th>
                        <th>Terlambat</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $peminjaman->pengembalian && $peminjaman->pengembalian->tanggal_kembali_aktual ? $peminjaman->pengembalian->tanggal_kembali_aktual->format('d/m/Y H:i') : '-' }}</td>
                        <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $peminjaman->pengembalian->kondisi_alat) }}</td>
                        <td>{{ $peminjaman->pengembalian->keterlambatan_hari }} Hari</td>
                        <td>Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="total-row">
                <span>TOTAL DENDA:</span>
                <span style="color: #009ef7;">Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="signature-grid">
            <div>
                <p>Peminjam,</p>
                <div class="signature-box"></div>
                <p>( {{ $peminjaman->user->name }} )</p>
            </div>
            <div>
                <p>Petugas Perpustakaan Alat,</p>
                <div class="signature-box"></div>
                <p>( ............................ )</p>
            </div>
        </div>

        <div class="footer">
            <p>Invoice ini dihasilkan secara otomatis oleh Sistem Lendify pada {{ date('d/m/Y H:i:s') }}.</p>
            <p>Terima kasih telah menggunakan layanan kami.</p>
        </div>
    </div>

</body>
</html>
