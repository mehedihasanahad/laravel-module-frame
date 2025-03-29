<?php

namespace App\Services\Image;
use Intervention\Image\Facades\Image;

trait ImageOptimizer
{
    /**
     * Reduce image size with specific width and height
     * @param  object  $file defined configuration, which is required
     * @param  string  $destination where image will be stored
     * @param  int  $width image width
     * @param  int  $height image height
     * @param  string  $fileNamePrefix image name prefix
     * @return array
     */
    function resizeImageAndMoveToDirectories(object $file, string $destination, int $width, int $height, string $fileNamePrefix): array {
        try {
            if (!file_exists($destination)) {
                mkdir( $destination, 0777, true );
            }
            $filename = uniqid($fileNamePrefix).$file->getClientOriginalName();
            $fileStoredPath = $destination.'/'.$filename;
            $img = Image::make($file->getRealPath())->resize($width, $height, function($constraint)
            {
                $constraint->aspectRatio();
            });
            $img->save($fileStoredPath);
            return [
                'status' => 200,
                'imagePath' => $fileStoredPath
            ];
        } catch (\Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
    }
}
