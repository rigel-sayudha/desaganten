<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikWilayah extends Model
{
    use HasFactory;

    protected $table = 'statistik_wilayah';

    protected $fillable = [
        'nama_wilayah',
        'jenis_wilayah',
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
    public static function boot()
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

    // Scope for ordering by jenis wilayah then nama
    public function scopeOrdered($query)
    {
        return $query->orderBy('jenis_wilayah')->orderBy('nama_wilayah');
    }

    // Get percentage of total population
    public function getPercentageAttribute()
    {
        $total = static::active()->sum('jumlah');
        return $total > 0 ? round(($this->jumlah / $total) * 100, 2) : 0;
    }

    // Get formatted jumlah
    public function getFormattedJumlahAttribute()
    {
        return number_format($this->jumlah, 0, ',', '.');
    }

    // Get formatted laki_laki
    public function getFormattedLakiLakiAttribute()
    {
        return number_format($this->laki_laki, 0, ',', '.');
    }

    // Get formatted perempuan
    public function getFormattedPerempuanAttribute()
    {
        return number_format($this->perempuan, 0, ',', '.');
    }

    // Static method to get total statistics
    public static function getTotalStatistics()
    {
        $active = static::active();
        
        return [
            'total_wilayah' => $active->count(),
            'total_penduduk' => $active->sum('jumlah'),
            'total_laki_laki' => $active->sum('laki_laki'),
            'total_perempuan' => $active->sum('perempuan'),
        ];
    }

    // Get available jenis wilayah options
    public static function getJenisWilayahOptions()
    {
        return [
            'Dusun' => 'Dusun',
            'RT' => 'Rukun Tetangga (RT)',
            'RW' => 'Rukun Warga (RW)',
            'Kelompok' => 'Kelompok',
            'Lingkungan' => 'Lingkungan',
            'Kampung' => 'Kampung',
            'Blok' => 'Blok',
            'Lainnya' => 'Lainnya'
        ];
    }
}
