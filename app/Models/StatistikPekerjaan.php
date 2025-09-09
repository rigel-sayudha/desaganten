<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'statistik_pekerjaan';

    protected $fillable = [
        'nama_pekerjaan',
        'laki_laki',
        'perempuan',
        'jumlah',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'laki_laki' => 'integer',
        'perempuan' => 'integer',
        'jumlah' => 'integer'
    ];

    // Auto calculate jumlah when saving
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            $model->jumlah = $model->laki_laki + $model->perempuan;
        });
    }

    // Scope for active records
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
