<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RekapSuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'rekap_surat_keluar';

    protected $fillable = [
        'tanggal_surat',
        'nomor_surat',
        'nama_pemohon',
        'jenis_surat',
        'untuk_keperluan',
        'status',
        'keterangan',
        'surat_type',
        'surat_id'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    // Scope untuk filter berdasarkan status
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    // Scope untuk filter berdasarkan periode
    public function scopePeriode($query, $start, $end)
    {
        return $query->whereBetween('tanggal_surat', [$start, $end]);
    }

    // Accessor untuk format tanggal Indonesia
    public function getTanggalSuratFormattedAttribute()
    {
        return $this->tanggal_surat->format('d F Y');
    }

    // Accessor untuk badge status
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'selesai':
                return 'bg-green-100 text-green-800';
            case 'diproses':
                return 'bg-blue-100 text-blue-800';
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'ditolak':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    // Accessor untuk badge status (HTML)
    public function getStatusBadgeAttribute()
    {
        $class = $this->status_badge_class;
        $statusText = ucfirst($this->status);
        
        return "<span class=\"px-2 py-1 text-xs font-medium {$class} rounded-full\">{$statusText}</span>";
    }
}
