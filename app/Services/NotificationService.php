<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Buat notifikasi surat diproses
     */
    public static function createSuratDiprosesNotification($userId, $suratId, $jenisSurat, $namaPemohon)
    {
        $jenisSuratDisplay = self::getJenisSuratDisplay($jenisSurat);
        
        return Notification::create([
            'user_id' => $userId,
            'type' => 'surat_diproses',
            'title' => 'Surat Sedang Diproses',
            'message' => "Surat {$jenisSuratDisplay} Anda sedang dalam proses verifikasi oleh admin desa.",
            'data' => [
                'surat_id' => $suratId,
                'jenis_surat' => $jenisSurat,
                'nama_pemohon' => $namaPemohon
            ]
        ]);
    }

    /**
     * Buat notifikasi surat selesai
     */
    public static function createSuratSelesaiNotification($userId, $suratId, $jenisSurat, $namaPemohon)
    {
        $jenisSuratDisplay = self::getJenisSuratDisplay($jenisSurat);
        
        return Notification::create([
            'user_id' => $userId,
            'type' => 'surat_selesai',
            'title' => 'Surat Telah Selesai',
            'message' => "Surat {$jenisSuratDisplay} Anda telah selesai diproses dan dapat diunduh.",
            'data' => [
                'surat_id' => $suratId,
                'jenis_surat' => $jenisSurat,
                'nama_pemohon' => $namaPemohon
            ]
        ]);
    }

    /**
     * Buat notifikasi surat approved (semua tahapan selesai)
     */
    public static function createSuratApprovedNotification($userId, $suratId, $jenisSurat, $namaPemohon)
    {
        $jenisSuratDisplay = self::getJenisSuratDisplay($jenisSurat);
        
        return Notification::create([
            'user_id' => $userId,
            'type' => 'surat_approved',
            'title' => 'Surat Telah Disetujui',
            'message' => "Surat {$jenisSuratDisplay} Anda telah melalui semua tahapan verifikasi dan disetujui. Surat dapat diunduh atau diambil di kantor desa.",
            'data' => [
                'surat_id' => $suratId,
                'jenis_surat' => $jenisSurat,
                'nama_pemohon' => $namaPemohon
            ]
        ]);
    }

    /**
     * Buat notifikasi surat ditolak
     */
    public static function createSuratDitolakNotification($userId, $suratId, $jenisSurat, $namaPemohon, $alasan = null)
    {
        $jenisSuratDisplay = self::getJenisSuratDisplay($jenisSurat);
        $message = "Surat {$jenisSuratDisplay} Anda ditolak.";
        if ($alasan) {
            $message .= " Alasan: {$alasan}";
        }
        
        return Notification::create([
            'user_id' => $userId,
            'type' => 'surat_ditolak',
            'title' => 'Surat Ditolak',
            'message' => $message,
            'data' => [
                'surat_id' => $suratId,
                'jenis_surat' => $jenisSurat,
                'nama_pemohon' => $namaPemohon,
                'alasan' => $alasan
            ]
        ]);
    }

    /**
     * Buat notifikasi khusus untuk verifikasi surat kematian
     */
    public static function createKematianVerifikasiNotification($userId, $suratId, $namaPelapor, $namaAlmarhum, $stage)
    {
        $stageNames = [
            1 => 'Verifikasi Dokumen',
            2 => 'Validasi Data',
            3 => 'Persetujuan Kepala Desa'
        ];
        
        $stageName = $stageNames[$stage] ?? "Tahap {$stage}";
        
        return Notification::create([
            'user_id' => $userId,
            'type' => 'kematian_verifikasi',
            'title' => 'Verifikasi Surat Kematian',
            'message' => "Surat Keterangan Kematian atas nama {$namaAlmarhum} sedang dalam tahap {$stageName}.",
            'data' => [
                'surat_id' => $suratId,
                'jenis_surat' => 'kematian',
                'nama_pelapor' => $namaPelapor,
                'nama_almarhum' => $namaAlmarhum,
                'stage' => $stage,
                'stage_name' => $stageName
            ]
        ]);
    }

    /**
     * Buat notifikasi surat kematian disetujui
     */
    public static function createKematianApprovedNotification($userId, $suratId, $namaPelapor, $namaAlmarhum)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => 'kematian_approved',
            'title' => 'Surat Kematian Disetujui',
            'message' => "Surat Keterangan Kematian atas nama {$namaAlmarhum} telah disetujui dan dapat diambil di kantor desa.",
            'data' => [
                'surat_id' => $suratId,
                'jenis_surat' => 'kematian',
                'nama_pelapor' => $namaPelapor,
                'nama_almarhum' => $namaAlmarhum
            ]
        ]);
    }

    /**
     * Buat notifikasi surat kematian ditolak
     */
    public static function createKematianDitolakNotification($userId, $suratId, $namaPelapor, $namaAlmarhum, $alasan = null)
    {
        $message = "Surat Keterangan Kematian atas nama {$namaAlmarhum} ditolak.";
        if ($alasan) {
            $message .= " Alasan: {$alasan}";
        }
        
        return Notification::create([
            'user_id' => $userId,
            'type' => 'kematian_ditolak',
            'title' => 'Surat Kematian Ditolak',
            'message' => $message,
            'data' => [
                'surat_id' => $suratId,
                'jenis_surat' => 'kematian',
                'nama_pelapor' => $namaPelapor,
                'nama_almarhum' => $namaAlmarhum,
                'alasan' => $alasan
            ]
        ]);
    }

    /**
     * Ambil notifikasi yang belum dibaca untuk user
     */
    public static function getUnreadNotifications($userId)
    {
        return Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Ambil semua notifikasi untuk user (termasuk yang sudah dibaca)
     */
    public static function getAllNotifications($userId, $limit = 10)
    {
        return Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public static function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca
     */
    public static function markAllAsRead($userId)
    {
        Notification::forUser($userId)
            ->unread()
            ->update(['read_at' => now()]);
    }

    /**
     * Hitung jumlah notifikasi yang belum dibaca
     */
    public static function getUnreadCount($userId)
    {
        return Notification::forUser($userId)->unread()->count();
    }

    /**
     * Helper untuk mendapatkan display name jenis surat
     */
    private static function getJenisSuratDisplay($jenisSurat)
    {
        $jenisSuratMap = [
            'domisili' => 'Keterangan Domisili',
            'ktp' => 'Pengantar KTP',
            'kk' => 'Pengantar KK',
            'skck' => 'Pengantar SKCK',
            'kematian' => 'Keterangan Kematian',
            'kelahiran' => 'Keterangan Kelahiran',
            'belum_menikah' => 'Keterangan Belum Menikah',
            'tidak_mampu' => 'Keterangan Tidak Mampu',
            'usaha' => 'Keterangan Usaha',
            'kehilangan' => 'Keterangan Kehilangan'
        ];

        return $jenisSuratMap[$jenisSurat] ?? ucwords(str_replace('_', ' ', $jenisSurat));
    }
}
