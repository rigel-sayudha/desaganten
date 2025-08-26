<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKematian extends Model
{
    use HasFactory;

    protected $table = 'surat_kematian';

    protected $fillable = [
        'nama_almarhum',
        'nik_almarhum',
        'tempat_lahir_almarhum',
        'tanggal_lahir_almarhum',
        'tanggal_kematian',
        'tempat_kematian',
        'sebab_kematian',
        'nama_pelapor',
        'nik_pelapor',
        'hubungan_pelapor',
        'alamat_pelapor',
        'keperluan',
        'status',
        'catatan_verifikasi',
        'user_id',
        'tahapan_verifikasi',
        'catatan_verifikasi_detail',
        'tanggal_verifikasi_terakhir'
    ];

    protected $casts = [
        'tanggal_lahir_almarhum' => 'date',
        'tanggal_kematian' => 'date',
        'tahapan_verifikasi' => 'array',
        'catatan_verifikasi_detail' => 'array',
        'tanggal_verifikasi_terakhir' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
