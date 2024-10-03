<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    public static function upload($requestFile, $path)
    {
        $imagePath = $requestFile->store($path, 'public');

        $manager = new ImageManager(new Driver());
        $thumbnail = $manager->read($requestFile);
        $thumbnail->cover(150, 150);
        $thumbnailPath = $path . '/thumbnails/' . basename($imagePath);
        Storage::disk('public')->put($thumbnailPath, (string) $thumbnail->encode());

        return basename($imagePath);
    }

    public static function delete($imagePath, $path)
    {
        if ($imagePath && Storage::disk('public')->exists($path . '/' . $imagePath)) {
            Storage::disk('public')->delete($path . '/' . $imagePath);
        }
        if ($imagePath && Storage::disk('public')->exists($path . '/thumbnails/' . $imagePath)) {
            Storage::disk('public')->delete($path . '/thumbnails/' . $imagePath);
        }
    }
}
