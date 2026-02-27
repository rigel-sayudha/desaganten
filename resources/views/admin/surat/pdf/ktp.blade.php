<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan KTP</title>
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
            font-size: 12pt;
            font-weight: normal;
            color: #000;
        }
        .content {
            padding: 10px 0;
            min-height: 500px;
        }
        .nomor-surat {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            text-decoration: underline;
            font-size: 12pt;
        }
        .isi-surat {
            text-align: justify;
            margin: 20px 0;
            text-indent: 30px;
        }
        .data-pemohon {
            margin: 20px 0;
            margin-left: 30px;
        }
        .data-pemohon table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-pemohon td {
            padding: 3px 0;
            vertical-align: top;
        }
        .data-pemohon td:first-child {
            width: 150px;
        }
        .data-pemohon td:nth-child(2) {
            width: 20px;
            text-align: center;
        }
        .ttd-section {
            margin-top: 40px;
            text-align: right;
            margin-right: 50px;
        }
        .ttd-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        .ttd-line {
            border-top: 1px solid #000;
            margin-top: 80px;
            padding-top: 5px;
        }
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        .keperluan {
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <div class="kop-text">
            <h1>PEMERINTAH KABUPATEN KARANGANYAR</h1>
            <h2>KECAMATAN GONDANGREJO</h2>
            <h2>DESA {{ strtoupper($surat->desa ?? 'KEMIRI') }}</h2>
            <h3>Alamat: Jl. Raya Kemiri No. 123, Kemiri, Gondangrejo, Karanganyar</h3>
            <h3>Kode Pos: 57173 | Telp: (0271) 123456</h3>
        </div>
    </div>

    <div class="content">
        <div class="nomor-surat">
            SURAT KETERANGAN KTP<br>
            NOMOR: {{ $surat->nomor_surat ?? 'SKK/001/'.date('m/Y') }}
        </div>

        <div class="isi-surat">
            Yang bertanda tangan di bawah ini, Kepala Desa {{ $surat->desa ?? 'Kemiri' }}, Kecamatan Gondangrejo, Kabupaten Karanganyar, dengan ini menerangkan bahwa:
        </div>

        <div class="data-pemohon">
            <table>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td>{{ $surat->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $surat->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $surat->jenis_kelamin ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ $surat->tempat_lahir ?? '-' }}, {{ $surat->tanggal_lahir ? date('d F Y', strtotime($surat->tanggal_lahir)) : '-' }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ $surat->pekerjaan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $surat->alamat ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ $surat->status ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="isi-surat">
            Adalah benar-benar penduduk Desa {{ $surat->desa ?? 'Kemiri' }}, Kecamatan Gondangrejo, Kabupaten Karanganyar dan memerlukan Kartu Tanda Penduduk (KTP).
        </div>

        <div class="keperluan">
            Keperluan: {{ $surat->keperluan ?? 'Pembuatan KTP' }}
        </div>

        <div class="isi-surat">
            Demikian surat keterangan ini dibuat dengan sebenarnya dan dapat dipergunakan sebagaimana mestinya.
        </div>

        <div class="ttd-section">
            <div class="ttd-box">
                {{ $surat->desa ?? 'Kemiri' }}, {{ date('d F Y') }}<br>
                Kepala Desa {{ $surat->desa ?? 'Kemiri' }}<br>
                <div class="ttd-line">
                    <strong>{{ $surat->kepala_desa ?? 'Nama Kepala Desa' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d F Y H:i:s') }} | Surat Keterangan KTP
    </div>
</body>
</html>
