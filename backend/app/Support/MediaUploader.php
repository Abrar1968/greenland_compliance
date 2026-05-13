<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MediaUploader
{
    public function storeImage(UploadedFile $file, string $directory, ?string $oldPath = null, int $maxWidth = 1920, int $quality = 82): string
    {
        if (! extension_loaded('gd')) {
            throw new RuntimeException('PHP GD extension is required for image upload processing.');
        }

        $info = getimagesize($file->getRealPath());
        if ($info === false) {
            throw new RuntimeException('Uploaded file is not a valid image.');
        }

        [$width, $height] = $info;
        $mime = $info['mime'] ?? '';
        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => throw new RuntimeException('Unsupported image type.'),
        };

        $source = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png' => imagecreatefrompng($file->getRealPath()),
            'image/webp' => imagecreatefromwebp($file->getRealPath()),
        };

        $targetWidth = min($width, $maxWidth);
        $targetHeight = (int) round($height * ($targetWidth / $width));
        $target = imagecreatetruecolor($targetWidth, $targetHeight);

        if (in_array($mime, ['image/png', 'image/webp'], true)) {
            imagealphablending($target, false);
            imagesavealpha($target, true);
            $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
            imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, $transparent);
        }

        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        $path = trim($directory, '/').'/'.uniqid('', true).'.'.$extension;
        $absolute = Storage::disk('public')->path($path);
        if (! is_dir(dirname($absolute))) {
            mkdir(dirname($absolute), 0775, true);
        }

        match ($extension) {
            'jpg' => imagejpeg($target, $absolute, $quality),
            'png' => imagepng($target, $absolute, 6),
            'webp' => imagewebp($target, $absolute, $quality),
        };

        imagedestroy($source);
        imagedestroy($target);

        $this->delete($oldPath);

        return $path;
    }

    public function storeFile(UploadedFile $file, string $directory, ?string $oldPath = null): string
    {
        $path = $file->store($directory, 'public');
        $this->delete($oldPath);

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
