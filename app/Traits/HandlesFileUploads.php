<?php

namespace App\Traits;

use App\Helpers\FileUploadHelper;
use Illuminate\Http\Request;

trait HandlesFileUploads
{
    /**
     * Handle multiple file uploads for a request
     *
     * @param Request $request
     * @param array $fileFields Associative array of field name => storage path
     * @return array
     */
    protected function handleFileUploads(Request $request, array $fileFields): array
    {
        $fileData = [];

        foreach ($fileFields as $fieldName => $storagePath) {
            $uploadedFile = FileUploadHelper::handleUpload($request, $fieldName, $storagePath);
            if ($uploadedFile) {
                $fileData[$fieldName] = $uploadedFile;
            }
        }

        return $fileData;
    }

    /**
     * Get file upload configuration for different surat types
     *
     * @param string $type
     * @return array
     */
    protected function getFileUploadConfig(string $type): array
    {
        $configs = [
            'kehilangan' => [
                'ktp_file' => 'surat_kehilangan/ktp',
                'file_ktp_pelapor' => 'surat_kehilangan/ktp_pelapor',
                'bukti_file' => 'surat_kehilangan/bukti',
                'file_laporan_polisi' => 'surat_kehilangan/laporan_polisi',
                'foto_file' => 'surat_kehilangan/foto',
                'file_kk' => 'surat_kehilangan/kk',
                'file_dokumen_tambahan' => 'surat_kehilangan/dokumen_tambahan'
            ],
            'ktp' => [
                'file_ktp' => 'ktp/ktp',
                'file_kk' => 'ktp/kk',
                'file_dokumen_tambahan' => 'ktp/dokumen_tambahan'
            ],
            'domisili' => [
                'file_ktp' => 'domisili/ktp',
                'file_kk' => 'domisili/kk',
                'file_pengantar_rt' => 'domisili/pengantar_rt',
                'file_dokumen_tambahan' => 'domisili/dokumen_tambahan'
            ],
            'usaha' => [
                'file_ktp' => 'usaha/ktp',
                'file_kk' => 'usaha/kk',
                'file_foto_usaha' => 'usaha/foto',
                'file_izin_usaha' => 'usaha/izin',
                'file_pengantar' => 'usaha/pengantar',
                'file_dokumen_tambahan' => 'usaha/dokumen_tambahan'
            ],
            // Tambahkan konfigurasi untuk jenis surat lainnya di sini
        ];

        return $configs[$type] ?? [];
    }
}
