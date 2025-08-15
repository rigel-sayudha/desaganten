<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Domisili</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .kop { text-align: center; margin-bottom: 16px; }
        .kop img { height: 60px; }
        .kop .title { font-size: 16px; font-weight: bold; }
        .kop .subtitle { font-size: 14px; }
        .garis { border-bottom: 2px solid #000; margin: 8px 0 16px 0; }
        .content { margin: 0 32px; }
        .table { width: 100%; margin-bottom: 16px; }
        .table td { padding: 4px 8px; }
        .ttd { margin-top: 32px; text-align: right; }
    </style>
</head>
<body>
    <div class="kop">
        <img src="{{ public_path('img/logo.png') }}" alt="Logo" style="float:left; margin-right:16px;">
        <div class="title">PEMERINTAH DESA GANTEN</div>
        <div class="subtitle">KECAMATAN KERJO KABUPATEN KARANGANYAR</div>
        <div class="subtitle">Jl. Raya Ganten No. 99, Telp. (0282) 123456</div>
        <div style="clear:both;"></div>
    </div>
    <div class="garis"></div>
    <div class="content">
        <div style="text-align:center; margin-bottom:16px;">
            <span style="font-size:15px; font-weight:bold; text-decoration:underline;">SURAT KETERANGAN DOMISILI</span><br>
            <span>Nomor: {{ $data->id }}/SKD/{{ date('Y') }}</span>
        </div>
        <p>Yang bertanda tangan di bawah ini, Kepala Desa Ganten, Kecamatan Kerjo, Kabupaten Karanganyar, menerangkan bahwa:</p>
        <table class="table">
            <tr><td style="width:150px;">NIK</td><td>: {{ $data->nik }}</td></tr>
            <tr><td>Nama</td><td>: {{ $data->nama }}</td></tr>
            <tr><td>Tempat/Tgl Lahir</td><td>: {{ $data->tempat_lahir }}, {{ $data->tanggal_lahir }}</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{ $data->jenis_kelamin }}</td></tr>
            <tr><td>Kewarganegaraan</td><td>: {{ $data->kewarganegaraan }}</td></tr>
            <tr><td>Agama</td><td>: {{ $data->agama }}</td></tr>
            <tr><td>Status</td><td>: {{ $data->status }}</td></tr>
            <tr><td>Pekerjaan</td><td>: {{ $data->pekerjaan }}</td></tr>
            <tr><td>Alamat</td><td>: {{ $data->alamat }}</td></tr>
        </table>
        <p>Benar yang bersangkutan berdomisili di Desa Ganten, Kecamatan Kerjo, Kabupaten Karanganyar.</p>
        <p>Surat keterangan ini dibuat untuk keperluan: <b>{{ $data->keperluan }}</b></p>
        <div class="ttd">
            <div>Ganten, {{ date('d-m-Y', strtotime($data->created_at)) }}</div>
            <div style="margin-top:60px;">Kepala Desa Ganten</div>
            <div style="margin-top:60px; font-weight:bold;">Bapak Suharto</div>
        </div>
    </div>
</body>
</html>
