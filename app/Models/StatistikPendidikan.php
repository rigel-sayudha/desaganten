<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikPendidikan extends Model
{
    use HasFactory;

    protected $table = 'statistik_pendidikan';

    protected $fillable = [
        'tingkat_pendidikan',
        'urutan',
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
        'jumlah' => 'integer',
        'urutan' => 'integer'
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
        return $query->where('is_active', true)->orderBy('urutan');
    }
}
