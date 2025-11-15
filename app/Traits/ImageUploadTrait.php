<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File; // Use File facade for direct file operations
use Intervention\Image\Laravel\Facades\Image;

trait ImageUploadTrait
{
    /**
     * Handle the image upload process.
     *
     * @param Request $request
     * @param Model $model
     * @param string $fieldName
     * @param string $storagePath The subdirectory within 'public/uploads/'
     * @param int|null $width
     * @param int|null $height
     * @return string|null The DB path (e.g., 'public/uploads/officers/image.png')
     */
    public function handleImageUpload(Request $request, Model $model, string $fieldName, string $storagePath, ?int $width = null, ?int $height = null): ?string
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        // 1. Delete old image if it exists
        if ($model->{$fieldName} && File::exists(base_path($model->{$fieldName}))) {
            File::delete(base_path($model->{$fieldName}));
        }

        $file = $request->file($fieldName);
        // Keep original extension, as requested
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // 2. Define DB path and Save path
        $dbPath = "public/uploads/{$storagePath}/{$fileName}";
        $savePath = base_path($dbPath); // This is equivalent to public_path("uploads/{$storagePath}/{$fileName}")

        // 3. Create directory if it doesn't exist
        $directory = public_path("uploads/{$storagePath}");
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $image = Image::read($file);

        if ($width && $height) {
            $image->resize($width, $height);
        }
        
        // 4. Save at 100% quality
        $image->save($savePath, 100);

        return $dbPath;
    }

     /**
     * Handle the image update process.
     *
     * @param Request $request
     * @param Model $model
     * @param string $fieldName
     * @param string $storagePath
     * @param int|null $width
     * @param int|null $height
     * @return string The final DB path
     */
    public function handleImageUpdate(Request $request, Model $model, string $fieldName, string $storagePath, ?int $width = null, ?int $height = null): string
    {
        if ($request->hasFile($fieldName)) {
            // New file is uploaded, run the full upload process
            return $this->handleImageUpload($request, $model, $fieldName, $storagePath, $width, $height);
        }

        // No new file, return the existing image path
        return $model->{$fieldName};
    }
}