<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BelumMenikah extends Model
{
    use HasFactory;

    protected $table = 'belum_menikah';

    protected $fillable = [
        'user_id',
        'nama',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pekerjaan',
        'no_telepon',
        'alamat',
        'nama_orang_tua',
        'pekerjaan_orang_tua',
        'alamat_orang_tua',
        'keperluan',
        'status',
        'keterangan_admin',
        'tahapan_verifikasi',
        'catatan_verifikasi',
        'tanggal_verifikasi_terakhir',
        'tanggal_diproses',
        'file_surat'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_diproses' => 'datetime',
        'tahapan_verifikasi' => 'array',
        'tanggal_verifikasi_terakhir' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'diproses' => 'bg-blue-100 text-blue-800',
            'selesai' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusTextAttribute()
    {
        $statusText = [
            'pending' => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];

        return $statusText[$this->status] ?? 'Tidak Diketahui';
    }
}
