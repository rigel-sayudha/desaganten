<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Kematian</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
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
            margin-bottom: 12px;
            position: relative;
            min-height: 60px;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 5px;
            width: 50px;
            height: 50px;
        }
        .kop-text {
            text-align: center;
            margin-left: 70px;
            margin-right: 70px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 15pt;
            font-weight: bold;
            color: #000;
        }
        .kop-surat h2 {
            margin: 2px 0;
            font-size: 13pt;
            font-weight: bold;
            color: #000;
        }
        .kop-surat h3 {
            margin: 2px 0;
            font-size: 12pt;
            font-weight: bold;
            color: #000;
        }
        .kop-surat p {
            margin: 1px 0;
            font-size: 9pt;
        }
        .nomor-surat {
            text-align: center;
            margin: 12px 0;
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
        }
        .content {
            text-align: justify;
            margin: 8px 0;
            line-height: 1.3;
            flex: 1;
        }
        .data-table {
            margin: 8px 0;
            width: 100%;
        }
        .data-table tr td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 9.5pt;
        }
        .data-table tr td:first-child {
            width: 160px;
        }
        .data-table tr td:nth-child(2) {
            width: 15px;
            text-align: center;
        }
        .section-title {
            font-weight: bold;
            margin: 10px 0 5px 0;
            text-decoration: underline;
            font-size: 10pt;
        }
        .pejabat-info table {
            margin: 6px 0 !important;
        }
        .pejabat-info td {
            padding: 1px 0 !important;
            font-size: 9.5pt !important;
        }
        .content p {
            margin: 6px 0;
            font-size: 9.5pt;
        }
        .ttd {
            margin-top: 15px;
            text-align: right;
        }
        .ttd-content {
            display: inline-block;
            text-align: center;
            margin-right: 60px;
        }
        .ttd-content p {
            margin: 2px 0;
            font-size: 9.5pt;
        }
        .ttd-space {
            margin: 30px 0 6px 0;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <div class="logo">
            <img src="{{ public_path('img/logo-karanganyar.png') }}" alt="Logo Desa Ganten" style="width: 50px; height: 50px;">
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
        Surat Keterangan Kematian<br>
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

        <div class="section-title">DATA ORANG YANG MENINGGAL DUNIA:</div>
        <table class="data-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ strtoupper($surat->nama_almarhum ?? $surat->nama ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $surat->nik_almarhum ?? $surat->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat/Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $surat->tempat_lahir_almarhum ?? $surat->tempat_lahir ?? '-' }}, {{ isset($surat->tanggal_lahir_almarhum) ? \Carbon\Carbon::parse($surat->tanggal_lahir_almarhum)->format('d F Y') : (isset($surat->tanggal_lahir) ? \Carbon\Carbon::parse($surat->tanggal_lahir)->format('d F Y') : '-') }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $surat->jenis_kelamin_almarhum ?? $surat->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ $surat->agama_almarhum ?? $surat->agama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat Terakhir</td>
                <td>:</td>
                <td>{{ $surat->alamat_almarhum ?? $surat->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Kematian</td>
                <td>:</td>
                <td>{{ isset($surat->tanggal_kematian) ? \Carbon\Carbon::parse($surat->tanggal_kematian)->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Tempat Kematian</td>
                <td>:</td>
                <td>{{ $surat->tempat_kematian ?? '-' }}</td>
            </tr>
            <tr>
                <td>Sebab Kematian</td>
                <td>:</td>
                <td>{{ $surat->sebab_kematian ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">DATA PELAPOR:</div>
        <table class="data-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ strtoupper($surat->nama_pelapor ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $surat->nik_pelapor ?? '-' }}</td>
            </tr>
            <tr>
                <td>Hubungan dengan Almarhum</td>
                <td>:</td>
                <td>{{ $surat->hubungan_pelapor ?? '-' }}</td>
            </tr>
        </table>

        <p>Berdasarkan keterangan pelapor dan data yang ada, bahwa orang tersebut di atas benar-benar telah meninggal dunia dan merupakan penduduk Desa Ganten, Kecamatan Kerjo, Kabupaten Karanganyar.</p>

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
