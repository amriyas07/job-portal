<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileTrait
{
    /**
     * Store the uploaded file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string|null
     */
    public function storeFile($file, string $path = 'uploads')
    {
        if ($file) {
            // Ensure directory exists inside storage/app/public
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs($path, $fileName, 'public');

            // returns relative path (use asset("storage/$filePath") to access)
            return $filePath;
        }

        return null;
    }

        /**
     * Delete the stored file.
     */
    public function deleteFile(?string $filePath): bool
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }
}
