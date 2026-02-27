<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HandlesSuratFiles;

class SuratKehilangan extends Model
{
    use HasFactory;
    use HandlesSuratFiles;

    protected $table = 'surat_kehilangan';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_perkawinan',
        'kewarganegaraan',
        'pekerjaan',
        'alamat',
        'barang_hilang',
        'deskripsi_barang',
        'tempat_kehilangan',
        'tanggal_kehilangan',
        'waktu_kehilangan',
        'kronologi_kehilangan',
        'no_laporan_polisi',
        'tanggal_laporan_polisi',
        'keperluan',
        'status',
        'catatan_verifikasi',
        'user_id',
        'tahapan_verifikasi',
        'catatan_verifikasi_detail',
        'tanggal_verifikasi_terakhir'
    ];

    protected $casts = [
        'tahapan_verifikasi' => 'array',
        'catatan_verifikasi_detail' => 'array',
        'tanggal_lahir' => 'date',
        'tanggal_kehilangan' => 'date',
        'tanggal_laporan_polisi' => 'date',
        'tanggal_verifikasi_terakhir' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
