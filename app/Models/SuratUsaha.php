<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratUsaha extends Model
{
    use HasFactory;

    protected $table = 'surat_usaha';

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
        'nama_usaha',
        'jenis_usaha',
        'alamat_usaha',
        'tanggal_mulai_usaha',
        'lama_usaha',
        'modal_usaha',
        'omzet_usaha',
        'deskripsi_usaha',
        'keperluan',
        'status',
        'catatan_verifikasi',
        'user_id',
        'tahapan_verifikasi',
        'catatan_verifikasi_detail',
        'tanggal_verifikasi_terakhir',
        // File upload fields
        'file_ktp',
        'file_kk',
        'file_foto_usaha',
        'file_izin_usaha',
        'file_pengantar'
    ];

    protected $casts = [
        'tahapan_verifikasi' => 'array',
        'catatan_verifikasi_detail' => 'array',
        'tanggal_lahir' => 'date',
        'tanggal_mulai_usaha' => 'date',
        'tanggal_verifikasi_terakhir' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
