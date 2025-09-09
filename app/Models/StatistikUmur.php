<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikUmur extends Model
{
    use HasFactory;

    protected $table = 'statistik_umur';

    protected $fillable = [
        'kelompok_umur',
        'usia_min',
        'usia_max',
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
        'usia_min' => 'integer',
        'usia_max' => 'integer'
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

    // Accessor for formatted age range
    public function getFormattedRangeAttribute()
    {
        if ($this->usia_max) {
            return $this->usia_min . '-' . $this->usia_max . ' Tahun';
        }
        return $this->usia_min . '+ Tahun';
    }
}
