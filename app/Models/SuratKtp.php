<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKtp extends Model
{
    use HasFactory;
    protected $table = 'surat_ktp';
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'pekerjaan',
        'alamat',
        'keperluan',
        'status',
        'file_ktp',
        'file_kk',
        'file_dokumen_tambahan',
        'file_uploads',
        'tahapan_verifikasi',
        'catatan_verifikasi',
        'tanggal_verifikasi_terakhir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'file_uploads' => 'array',
        'tahapan_verifikasi' => 'array',
        'tanggal_verifikasi_terakhir' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
