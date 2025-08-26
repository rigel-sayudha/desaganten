<!DOCTYPE html>
<html>
<head>
    <title>Surat Keterangan Belum Menikah</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            float: left;
            margin-right: 20px;
        }
        
        .header-text {
            margin-left: 100px;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .header p {
            font-size: 11pt;
            margin: 2px 0;
        }
        
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 30px 0 20px 0;
            text-transform: uppercase;
        }
        
        .nomor {
            text-align: center;
            font-size: 12pt;
            margin-bottom: 30px;
        }
        
        .content {
            text-align: justify;
            margin-bottom: 30px;
        }
        
        .content p {
            margin-bottom: 15px;
        }
        
        .data-table {
            margin: 20px 0;
        }
        
        .data-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .data-table .label {
            width: 200px;
            font-weight: normal;
        }
        
        .data-table .separator {
            width: 20px;
            text-align: center;
        }
        
        .signature {
            margin-top: 50px;
        }
        
        .signature-left {
            float: left;
            width: 45%;
            text-align: center;
        }
        
        .signature-right {
            float: right;
            width: 45%;
            text-align: center;
        }
        
        .signature-space {
            height: 80px;
        }
        
        .clear {
            clear: both;
        }
        
        .verification-info {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 20px;
            font-size: 10pt;
        }
        
        .verification-info h4 {
            margin: 0 0 10px 0;
            font-size: 11pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/logo karanganyar.png') }}" alt="Logo" class="logo">
        <div class="header-text">
            <h1>Pemerintah Kabupaten Karanganyar</h1>
            <h2>Kecamatan Gondangrejo</h2>
            <h1>Desa Ganten</h1>
            <p>Alamat: Jl. Raya Ganten No. 123, Gondangrejo, Karanganyar</p>
            <p>Telepon: (0271) 123456 | Email: desaganten@karanganyar.go.id</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="title">
        Surat Keterangan Belum Menikah
    </div>

    <div class="nomor">
        Nomor: {{ sprintf('%03d', $surat->id) }}/SKBM/{{ date('Y') }}
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala Desa Ganten, Kecamatan Gondangrejo, Kabupaten Karanganyar, dengan ini menerangkan bahwa:</p>

        <table class="data-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="separator">:</td>
                <td><strong>{{ strtoupper($surat->nama) }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="separator">:</td>
                <td>{{ $surat->nik }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="separator">:</td>
                <td>{{ ucfirst($surat->jenis_kelamin) }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="separator">:</td>
                <td>{{ $surat->tempat_lahir }}, {{ \Carbon\Carbon::parse($surat->tanggal_lahir)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td class="separator">:</td>
                <td>{{ ucfirst($surat->agama) }}</td>
            </tr>
            <tr>
                <td class="label">Pekerjaan</td>
                <td class="separator">:</td>
                <td>{{ ucfirst($surat->pekerjaan) }}</td>
            </tr>
            <tr>
                <td class="label">Kewarganegaraan</td>
                <td class="separator">:</td>
                <td>{{ ucfirst($surat->kewarganegaraan) }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td>{{ $surat->alamat }}</td>
            </tr>
            @if($surat->nama_ayah)
            <tr>
                <td class="label">Nama Ayah</td>
                <td class="separator">:</td>
                <td>{{ $surat->nama_ayah }}</td>
            </tr>
            @endif
            @if($surat->nama_ibu)
            <tr>
                <td class="label">Nama Ibu</td>
                <td class="separator">:</td>
                <td>{{ $surat->nama_ibu }}</td>
            </tr>
            @endif
        </table>

        <p>Adalah benar-benar penduduk Desa Ganten, Kecamatan Gondangrejo, Kabupaten Karanganyar, dan sampai dengan dikeluarkannya surat keterangan ini yang bersangkutan berstatus <strong>BELUM MENIKAH</strong>.</p>

        <p>Surat keterangan ini dibuat berdasarkan keterangan yang bersangkutan dan untuk {{ $surat->keperluan ?? 'keperluan yang bersangkutan' }}.</p>

        <p>Demikian surat keterangan ini dibuat dengan sebenar-benarnya dan dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <div class="signature-left">
            <p>Mengetahui,</p>
            <p><strong>Ketua RT {{ $surat->rt ?? '001' }}/RW {{ $surat->rw ?? '001' }}</strong></p>
            <div class="signature-space"></div>
            <p><strong><u>Slamet Riyadi</u></strong></p>
        </div>
        
        <div class="signature-right">
            <p>Ganten, {{ $tanggal_cetak }}</p>
            <p><strong>Kepala Desa Ganten</strong></p>
            <div class="signature-space"></div>
            <p><strong><u>Budi Santoso, S.AP</u></strong></p>
            <p>NIP. 19701010 199003 1 001</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="verification-info">
        <h4>Informasi Verifikasi</h4>
        <p><strong>Status:</strong> {{ ucfirst($surat->status) }}</p>
        <p><strong>Tanggal Pengajuan:</strong> {{ $surat->created_at->format('d F Y H:i') }}</p>
        @if($surat->tanggal_verifikasi_terakhir)
        <p><strong>Tanggal Verifikasi:</strong> {{ \Carbon\Carbon::parse($surat->tanggal_verifikasi_terakhir)->format('d F Y H:i') }}</p>
        @endif
        <p><strong>Dokumen ini telah diverifikasi secara digital oleh sistem</strong></p>
    </div>
</body>
</html>
