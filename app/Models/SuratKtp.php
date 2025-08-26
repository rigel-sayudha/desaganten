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
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
