<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Usaha</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            height: 100vh;
            
        }
        .kop-surat {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
            position: relative;
            min-height: 70px;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 5px;
            width: 60px;
            height: 60px;
        }
        .kop-text {
            text-align: center;
            margin-left: 80px;
            margin-right: 80px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            color: #000;
        }
        .kop-surat h2 {
            margin: 2px 0;
            font-size: 14pt;
            font-weight: bold;
            color: #000;
        }
        .kop-surat h3 {
            margin: 2px 0;
            font-size: 13pt;
            font-weight: bold;
            color: #000;
        }
        .kop-surat p {
            margin: 1px 0;
            font-size: 10pt;
        }
        .nomor-surat {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            font-size: 13pt;
            text-decoration: underline;
        }
        .content {
            text-align: justify;
            margin: 10px 0;
            line-height: 1.4;
            
        }
        .data-table {
            margin: 12px 0;
            width: 100%;
        }
        .data-table tr td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .data-table tr td:first-child {
            width: 180px;
        }
        .data-table tr td:nth-child(2) {
            width: 15px;
            text-align: center;
        }
        .usaha-table {
            margin: 12px 0;
            width: 100%;
        }
        .usaha-table tr td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .usaha-table tr td:first-child {
            width: 180px;
        }
        .usaha-table tr td:nth-child(2) {
            width: 15px;
            text-align: center;
        }
        .pejabat-info table {
            margin: 8px 0 !important;
        }
        .pejabat-info td {
            padding: 1px 0 !important;
            font-size: 10.5pt !important;
        }
        .content p {
            margin: 8px 0;
            font-size: 10.5pt;
        }
        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            font-size: 10.5pt;
        }
        .signature-left {
            width: 50%;
            text-align: left;
        }
        .signature-right {
            width: 250px;
            text-align: center;
        }
        .signature-space {
            height: 60px;
        }
        .penutup {
            margin: 10px 0;
            font-size: 10.5pt;
        }
        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80pt;
            color: rgba(0,0,0,0.05);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Header/Kop Surat -->
  <div class="kop-surat">
        <div class="logo">
            <img src="/home/desagant/public_html/img/logo-karanganyar.png" alt="Logo Desa Ganten" style="width: 60px; height: 60px;">
        </div>
        <div class="kop-text">
            <h1>PEMERINTAH KABUPATEN KARANGANYAR</h1>
            <h2>KECAMATAN KERJO</h2>
            <h3>DESA GANTEN</h3>
            <p>Geneng, RT 004 Rw 004, Ganten, Kerjo, Karanganyar</p>
            <p>Telp: 081292252634, Kode Pos: 57753</p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        SURAT KETERANGAN USAHA<br>
        Nomor: {{ str_pad($surat->id, 3, '0', STR_PAD_LEFT) }}/SKU/{{ date('m') }}/{{ date('Y') }}
    </div>

    <!-- Content -->
    <div class="content">
        <p>Yang bertanda tangan di bawah ini Kepala Desa Ganten Kecamatan Ganten Kabupaten Karanganyar Provinsi Jawa Tengah, dengan ini menerangkan bahwa:</p>
        
        <!-- Data Pemohon -->
        <table class="data-table">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><strong>{{ strtoupper($surat->nama_lengkap) }}</strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $surat->nik ?? Auth::user()->nik }}</td>
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
                <td>Kewarganegaraan</td>
                <td>:</td>
                <td>{{ $surat->kewarganegaraan ?? 'WNI' }}</td>
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

        <p>Adalah benar bahwa nama tersebut di atas memiliki usaha dengan data sebagai berikut:</p>

        <!-- Data Usaha -->
        <table class="usaha-table">
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
                <td>Lama Usaha</td>
                <td>:</td>
                <td>{{ $surat->lama_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <td>Modal Usaha</td>
                <td>:</td>
                <td>{{ $surat->modal_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <td>Omzet per Bulan</td>
                <td>:</td>
                <td>{{ $surat->omzet_usaha ?? '-' }}</td>
            </tr>
        </table>

        <div class="penutup">
            <p>Surat keterangan ini dibuat dengan sebenarnya dan dapat dipergunakan untuk <strong>{{ $surat->keperluan }}</strong>.</p>
            
            <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>

        <div class="signature-section">
            <div class="signature-right">
                <p>Ganten, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                <p><strong>{{ \App\Models\Setting::get('kepala_desa_jabatan', 'Kepala Desa Ganten') }}</strong></p>
                <div class="signature-space"></div>
                <p><strong><u>{{ \App\Models\Setting::get('kepala_desa_nama', 'Munadi') }}</u></strong></p>
            </div>
        </div>

    </div>
</body>
</html>
