<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <tit        .content {
            text-align: justify;
            margin: 10px 0;
            line-height: 1.4;
            min-height: 500px;
        }at Keterangan Kelahiran</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9.5pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            height: 100vh;
            
        }
        .kop-surat {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
            position: relative;
            min-height: 55px;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 5px;
            width: 45px;
            height: 45px;
        }
        .kop-text {
            text-align: center;
            margin-left: 60px;
            margin-right: 60px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            color: #000;
        }
        .kop-surat h2 {
            margin: 1px 0;
            font-size: 12pt;
            font-weight: bold;
            color: #000;
        }
        .kop-surat h3 {
            margin: 1px 0;
            font-size: 11pt;
            font-weight: bold;
            color: #000;
        }
        .kop-surat p {
            margin: 0.5px 0;
            font-size: 8.5pt;
        }
        .nomor-surat {
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
            font-size: 11pt;
            text-decoration: underline;
        }
        .content {
            text-align: justify;
            margin: 6px 0;
            line-height: 1.2;
            
        }
        .data-table {
            margin: 6px 0;
            width: 100%;
        }
        .data-table tr td {
            padding: 0.5px 0;
            vertical-align: top;
            font-size: 9pt;
        }
        .data-table tr td:first-child {
            width: 140px;
        }
        .data-table tr td:nth-child(2) {
            width: 12px;
            text-align: center;
        }
        .section-title {
            font-weight: bold;
            margin: 8px 0 4px 0;
            text-decoration: underline;
            font-size: 9.5pt;
        }
        .pejabat-info table {
            margin: 4px 0 !important;
        }
        .pejabat-info td {
            padding: 0.5px 0 !important;
            font-size: 9pt !important;
        }
        .content p {
            margin: 4px 0;
            font-size: 9pt;
        }
        .ttd {
            margin-top: 12px;
            text-align: right;
        }
        .ttd-content {
            display: inline-block;
            text-align: center;
            margin-right: 50px;
        }
        .ttd-content p {
            margin: 1px 0;
            font-size: 9pt;
        }
        .ttd-space {
            margin: 25px 0 4px 0;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <div class="logo">
            <img src="/home/desagant/public_html/img/logo-karanganyar.png" alt="Logo Desa Ganten" style="width: 45px; height: 45px;">
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
        Surat Keterangan Kelahiran<br>
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

        <div class="section-title">DATA BAYI YANG LAHIR:</div>
        <table class="data-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>
                    @php
                        $namaBayi = $surat->nama_anak ?? $surat->nama_bayi ?? $surat->nama ?? '-';
                    @endphp
                    <strong>{{ is_string($namaBayi) ? strtoupper($namaBayi) : '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $surat->jenis_kelamin_anak ?? $surat->jenis_kelamin_bayi ?? $surat->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat Lahir</td>
                <td>:</td>
                <td>{{ $surat->tempat_lahir_anak ?? $surat->tempat_lahir ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td>:</td>
                <td>
                    @php
                        $tglAnak = $surat->tanggal_lahir_anak ?? null;
                        $tglUmum = $surat->tanggal_lahir ?? null;
                        $formatTgl = function ($val) {
                            try {
                                if (!empty($val)) { return \Carbon\Carbon::parse($val)->format('d F Y'); }
                            } catch (\Throwable $e) { /* ignore */ }
                            return '-';
                        };
                    @endphp
                    {{ $tglAnak ? $formatTgl($tglAnak) : ($tglUmum ? $formatTgl($tglUmum) : '-') }}
                </td>
            </tr>
            <tr>
                <td>Jam Lahir</td>
                <td>:</td>
                <td>{{ $surat->jam_lahir ?? $surat->waktu_lahir ?? '-' }} WIB</td>
            </tr>
            <tr>
                <td>Berat Badan</td>
                <td>:</td>
                <td>{{ $surat->berat_badan ?? '-' }} gram</td>
            </tr>
            <tr>
                <td>Panjang Badan</td>
                <td>:</td>
                <td>{{ $surat->panjang_badan ?? '-' }} cm</td>
            </tr>
        </table>

        <div class="section-title">DATA ORANG TUA:</div>
        <table class="data-table">
            <!-- AYAH -->
            <tr>
                <td><strong>AYAH:</strong></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ strtoupper($surat->nama_ayah ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $surat->nik_ayah ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat/Tanggal Lahir</td>
                <td>:</td>
                <td>
                    @php
                        $tglAyah = $surat->tanggal_lahir_ayah ?? null;
                    @endphp
                    {{ ($surat->tempat_lahir_ayah ?? '-') }}, {{ $tglAyah ? $formatTgl($tglAyah) : '-' }}
                </td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>{{ $surat->pekerjaan_ayah ?? '-' }}</td>
            </tr>
            <!-- IBU -->
            <tr>
                <td><strong>IBU:</strong></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ strtoupper($surat->nama_ibu ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $surat->nik_ibu ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat/Tanggal Lahir</td>
                <td>:</td>
                <td>
                    @php
                        $tglIbu = $surat->tanggal_lahir_ibu ?? null;
                    @endphp
                    {{ ($surat->tempat_lahir_ibu ?? '-') }}, {{ $tglIbu ? $formatTgl($tglIbu) : '-' }}
                </td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>{{ $surat->pekerjaan_ibu ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $surat->alamat_orang_tua ?? $surat->alamat_orangtua ?? $surat->alamat ?? '-' }}</td>
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
                <td>Hubungan dengan Bayi</td>
                <td>:</td>
                <td>{{ $surat->hubungan_pelapor ?? '-' }}</td>
            </tr>
        </table>

        <p>Berdasarkan keterangan pelapor dan data yang ada, bahwa kelahiran tersebut di atas benar-benar terjadi di wilayah Desa Ganten, Kecamatan Kerjo, Kabupaten Karanganyar.</p>

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
