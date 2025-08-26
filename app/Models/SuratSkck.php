<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratSkck extends Model
{
    use HasFactory;

    protected $table = 'surat_skck';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'kewarganegaraan',
        'pekerjaan',
        'alamat',
        'keperluan',
        'status',
        'catatan_verifikasi',
        'user_id',
        'tahapan_verifikasi',
        'catatan_verifikasi_detail',
        'tanggal_verifikasi_terakhir'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahapan_verifikasi' => 'array',
        'catatan_verifikasi_detail' => 'array',
        'tanggal_verifikasi_terakhir' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
