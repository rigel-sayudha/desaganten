<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKelahiran extends Model
{
    use HasFactory;

    protected $table = 'surat_kelahiran';

    protected $fillable = [
        'nama_bayi',
        'jenis_kelamin_bayi',
        'tempat_lahir',
        'tanggal_lahir',
        'waktu_lahir',
        'nama_ayah',
        'nik_ayah',
        'nama_ibu',
        'nik_ibu',
        'alamat_orangtua',
        'nama_pelapor',
        'nik_pelapor',
        'hubungan_pelapor',
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
