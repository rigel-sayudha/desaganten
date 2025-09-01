<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;
    protected $table = 'wilayah';
    protected $fillable = [
        'nama', 'jumlah', 'laki_laki', 'perempuan'
    ];
    
    /**
     * Relasi dengan User
     */
    public function users()
    {
        return $this->hasMany(User::class, 'wilayah_id');
    }
    
    /**
     * Accessor untuk nama_wilayah (gunakan nama jika ada)
     */
    public function getNamaWilayahAttribute()
    {
        return $this->nama;
    }
}
