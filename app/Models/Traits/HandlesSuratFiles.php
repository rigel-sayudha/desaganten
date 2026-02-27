<?php

namespace App\Models\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait HandlesSuratFiles
{
    /**
     * Upload file untuk surat
     *
     * @param UploadedFile|null $file
     * @param string $path
     * @return string|null
     */
    public function uploadSuratFile(?UploadedFile $file, string $path): ?string
    {
        if (!$file) {
            return null;
        }

        try {
            $filePath = $file->store($path, 'public');
            if ($filePath) {
                Log::info('File surat berhasil diupload', [
                    'path' => $filePath,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ]);
                return $filePath;
            }
            
            Log::error('Gagal menyimpan file surat', [
                'original_name' => $file->getClientOriginalName()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Error saat upload file surat: ' . $e->getMessage(), [
                'original_name' => $file->getClientOriginalName()
            ]);
            return null;
        }
    }

    /**
     * Hapus file surat
     *
     * @param string|null $path
     * @return bool
     */
    public function deleteSuratFile(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                Log::info('File surat berhasil dihapus', ['path' => $path]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Error saat menghapus file surat: ' . $e->getMessage(), [
                'path' => $path
            ]);
        }

        return false;
    }

    /**
     * Upload multiple files untuk surat
     *
     * @param array $files Array of UploadedFile objects dengan key sebagai field name
     * @param array $paths Array of paths dengan key yang sama dengan $files
     * @return array
     */
    public function uploadMultipleSuratFiles(array $files, array $paths): array
    {
        $uploadedFiles = [];

        foreach ($files as $field => $file) {
            if ($file instanceof UploadedFile && isset($paths[$field])) {
                $uploadedPath = $this->uploadSuratFile($file, $paths[$field]);
                if ($uploadedPath) {
                    $uploadedFiles[$field] = $uploadedPath;
                }
            }
        }

        return $uploadedFiles;
    }
}
