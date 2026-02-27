<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Usaha</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            color: #000;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .header p {
            font-size: 12px;
            margin: 2px 0;
        }
        
        .title {
            text-align: center;
            margin: 30px 0;
        }
        
        .title h3 {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0;
        }
        
        .nomor {
            text-align: center;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .content {
            font-size: 14px;
            margin: 20px 0;
            text-align: justify;
        }
        
        .content p {
            margin: 15px 0;
        }
        
        .data-table {
            margin: 20px 0;
            width: 100%;
        }
        
        .data-table tr {
            vertical-align: top;
        }
        
        .data-table td {
            padding: 5px 0;
            font-size: 14px;
        }
        
        .data-table td:first-child {
            width: 30%;
        }
        
        .data-table td:nth-child(2) {
            width: 5%;
            text-align: center;
        }
        
        .data-table td:last-child {
            width: 65%;
        }
        
        .signature {
            margin-top: 50px;
        }
        
        .signature-table {
            width: 100%;
        }
        
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        
        .signature-space {
            height: 80px;
        }
        
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
        }
        
        .stamp-area {
            margin: 20px 0;
            text-align: center;
            border: 2px dashed #ccc;
            padding: 30px;
            color: #999;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .stamp-area {
                border: none;
                color: #000;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>PEMERINTAH KABUPATEN KARANGANYAR</h1>
        <h1>KECAMATAN GANTEN</h1>
        <h2>DESA GANTEN</h2>
        <p>Alamat: Jl. Desa Ganten No. 123, Kec. Ganten, Kab. Karanganyar, Jawa Tengah</p>
        <p>Telepon: (0271) 123-4567 | Email: desaganten@karanganyarkab.go.id</p>
    </div>

    <!-- Title -->
    <div class="title">
        <h3>SURAT KETERANGAN USAHA</h3>
    </div>

    <!-- Nomor Surat -->
    <div class="nomor">
        <p>Nomor: {{ str_pad($surat->id, 3, '0', STR_PAD_LEFT) }}/SKU/{{ date('m') }}/{{ date('Y') }}</p>
    </div>

    <!-- Content -->
    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala Desa Ganten Kecamatan Ganten Kabupaten Karanganyar, dengan ini menerangkan bahwa:</p>
        
        <table class="data-table">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><strong>{{ strtoupper($surat->nama_lengkap) }}</strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $surat->nik }}</td>
            </tr>
            <tr>
                <td>Tempat/Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $surat->tempat_lahir }}, {{ \Carbon\Carbon::parse($surat->tanggal_lahir)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $surat->jenis_kelamin }}</td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ $surat->agama }}</td>
            </tr>
            <tr>
                <td>Status Perkawinan</td>
                <td>:</td>
                <td>{{ $surat->status_perkawinan }}</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>{{ $surat->pekerjaan }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $surat->alamat }}</td>
            </tr>
        </table>

        <p>Adalah benar-benar penduduk Desa Ganten yang memiliki dan menjalankan usaha dengan keterangan sebagai berikut:</p>

        <table class="data-table">
            <tr>
                <td>Nama Usaha</td>
                <td>:</td>
                <td><strong>{{ strtoupper($surat->nama_usaha) }}</strong></td>
            </tr>
            <tr>
                <td>Jenis Usaha</td>
                <td>:</td>
                <td>{{ $surat->jenis_usaha }}</td>
            </tr>
            <tr>
                <td>Alamat Usaha</td>
                <td>:</td>
                <td>{{ $surat->alamat_usaha }}</td>
            </tr>
            <tr>
                <td>Tanggal Mulai Usaha</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($surat->tanggal_mulai_usaha)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Modal Usaha</td>
                <td>:</td>
                <td>Rp {{ number_format($surat->modal_usaha, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Deskripsi Usaha</td>
                <td>:</td>
                <td>{{ $surat->deskripsi_usaha }}</td>
            </tr>
        </table>

        <p>Yang bersangkutan telah menjalankan usaha tersebut dengan baik dan tidak melanggar ketentuan hukum yang berlaku.</p>

        <p>Surat keterangan ini dibuat untuk {{ $surat->keperluan }} dan dapat digunakan seperlunya.</p>

        <p>Demikian surat keterangan ini dibuat dengan sebenarnya dan dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <!-- Signature -->
    <div class="signature">
        <table class="signature-table">
            <tr>
                <td></td>
                <td>
                    <p>Ganten, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                    <p><strong>Kepala Desa Ganten</strong></p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div class="signature-space"></div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <p><strong><span class="underline">NAMA KEPALA DESA</span></strong></p>
                    <p>NIP. 123456789012345678</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Stamp Area -->
    <div class="stamp-area">
        <p><strong>TEMPAT STEMPEL/CAP DESA</strong></p>
    </div>

    <!-- Footer Note -->
    <div style="margin-top: 30px; font-size: 12px; text-align: center; color: #666;">
        <p>Surat ini diterbitkan secara elektronik dan sah tanpa tanda tangan basah</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i:s') }} WIB</p>
    </div>
</body>
</html>
