<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Belum Menikah</title>
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
            display: flex;
            flex-direction: column;
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
            min-height: 500px;
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
        .ttd {
            margin-top: 20px;
            text-align: right;
        }
        .ttd-content {
            display: inline-block;
            text-align: center;
            margin-right: 60px;
        }
        .ttd-content p {
            margin: 3px 0;
            font-size: 10.5pt;
        }
        .ttd-space {
            margin: 40px 0 8px 0;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <div class="logo">
            <img src="{{ base_path('img/logo-karanganyar.png') }}" alt="Logo Desa Ganten" style="width: 60px; height: 60px;">
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

    <div class="nomor-surat">
        Surat Keterangan Belum Menikah<br>
        Nomor: {{ sprintf('%03d', $surat->id) }}/{{ date('m') }}/{{ date('Y') }}
    </div>

    <div class="content">
        <p><strong>Yang bertanda tangan dibawah ini:</strong></p>
        
        <div class="pejabat-info">
            <table style="margin: 10px 0;">
                <tr>
                    <td style="width: 100px;">Nama</td>
                    <td style="width: 20px;">:</td>
                    <td>{{ \App\Models\Setting::get('kepala_desa_nama', 'Munadi') }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ \App\Models\Setting::get('kepala_desa_jabatan', 'Kepala Desa Ganten') }}</td>
                </tr>
            </table>
        </div>

        <p><strong>Dengan ini menerangkan bahwa:</strong></p>

        <table class="data-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ strtoupper($surat->nama) }}</strong></td>
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
                <td>Kewarganegaraan</td>
                <td>:</td>
                <td>{{ $surat->kewarganegaraan }}</td>
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

        <p>Berdasarkan pernyataan, bahwa yang bersangkutan benar-benar penduduk yang berdomisili di Desa Ganten, Kecamatan Kerjo, Kabupaten Karanganyar, dan <strong>sampai saat ini belum pernah menikah</strong>.</p>

        <p>Surat keterangan ini dibuat untuk keperluan <strong>{{ $surat->keperluan }}</strong>.</p>

        <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="ttd">
        <div class="ttd-content">
            <p>Ganten, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p><strong>{{ \App\Models\Setting::get('kepala_desa_jabatan', 'Kepala Desa Ganten') }}</strong></p>
            <div class="ttd-space"></div>
            <p><strong><u>{{ \App\Models\Setting::get('kepala_desa_nama', 'Munadi') }}</u></strong></p>
        </div>
    </div>
</body>
</html>
