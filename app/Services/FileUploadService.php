<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload single file
     */
    public function uploadFile(?UploadedFile $file, string $path): ?string
    {
        if (!$file) {
            return null;
        }

        try {
            $filePath = $file->store($path, 'public');
            if ($filePath) {
                Log::info('File berhasil diupload', [
                    'path' => $filePath,
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ]);
                return $filePath;
            }
            Log::error('Gagal menyimpan file');
            return null;
        } catch (\Exception $e) {
            Log::error('Error saat upload file: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle multiple file uploads
     */
    public function handleUploads($request, array $fileConfig): array
    {
        $uploadedFiles = [];

        foreach ($fileConfig as $field => $path) {
            if ($request->hasFile($field)) {
                $uploadedPath = $this->uploadFile(
                    $request->file($field),
                    $path
                );
                if ($uploadedPath) {
                    $uploadedFiles[$field] = $uploadedPath;
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Get upload configuration for different surat types
     */
    public function getSuratUploadConfig(string $type): array
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
            ]
        ];

        return $configs[$type] ?? [];
    }

    /**
     * Handle file uploads for specific surat type
     */
    public function handleSuratUploads($request, string $type): array
    {
        $config = $this->getSuratUploadConfig($type);
        return $this->handleUploads($request, $config);
    }
}
