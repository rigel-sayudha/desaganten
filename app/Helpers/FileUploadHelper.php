<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    /**
     * Upload file dan kembalikan path file jika berhasil
     *
     * @param UploadedFile|null $file
     * @param string $path
     * @param string $disk
     * @return string|null
     */
    public static function uploadFile(?UploadedFile $file, string $path, string $disk = 'public'): ?string
    {
        if (!$file) {
            return null;
        }

        try {
            $filePath = $file->store($path, $disk);
            
            if ($filePath) {
                Log::info('File uploaded successfully', [
                    'original_name' => $file->getClientOriginalName(),
                    'stored_path' => $filePath,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
                return $filePath;
            } else {
                Log::error('Failed to store file', [
                    'original_name' => $file->getClientOriginalName(),
                    'intended_path' => $path
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error uploading file: ' . $e->getMessage(), [
                'original_name' => $file->getClientOriginalName(),
                'intended_path' => $path,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Upload multiple files dan return array of paths
     *
     * @param array $files Array of UploadedFile objects
     * @param string $path Base path for storage
     * @param string $disk Storage disk to use
     * @return array Array of file paths or nulls
     */
    public static function uploadMultipleFiles(array $files, string $path, string $disk = 'public'): array
    {
        $uploadedFiles = [];
        
        foreach ($files as $key => $file) {
            if ($file instanceof UploadedFile) {
                $uploadedFiles[$key] = self::uploadFile($file, $path, $disk);
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Delete file if exists
     *
     * @param string|null $path
     * @param string $disk
     * @return bool
     */
    public static function deleteFile(?string $path, string $disk = 'public'): bool
    {
        if (!$path) {
            return false;
        }

        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
                Log::info('File deleted successfully', ['path' => $path]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage(), [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
        }

        return false;
    }

    /**
     * Handle file upload for form request
     *
     * @param \Illuminate\Http\Request $request
     * @param string $fieldName
     * @param string $path
     * @param string $disk
     * @return string|null
     */
    public static function handleUpload($request, string $fieldName, string $path, string $disk = 'public'): ?string
    {
        if ($request->hasFile($fieldName)) {
            return self::uploadFile($request->file($fieldName), $path, $disk);
        }
        return null;
    }
}
