<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageOptimizationService
{
    protected int $maxWidth = 1200;

    protected int $maxHeight = 1200;

    protected int $quality = 80;

    /**
     * Optimize an uploaded image: resize if needed and convert to WebP.
     *
     * @param  UploadedFile  $file  The uploaded image file
     * @param  string  $directory  Storage directory (e.g., 'products')
     * @return string The storage path of the optimized image
     */
    public function optimize(UploadedFile $file, string $directory = 'products'): string
    {
        $image = Image::read($file->getRealPath());

        // Scale down proportionally if either dimension exceeds max
        $image->scaleDown(width: $this->maxWidth, height: $this->maxHeight);

        // Encode to WebP format with quality setting
        $encoded = $image->toWebp($this->quality);

        // Generate a unique filename with .webp extension
        $filename = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webp';
        $path = $directory . '/' . $filename;

        // Store the optimized image in public disk
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Serve an optimized image from storage with long-lived Cache-Control headers.
     * Cache for 30 days (2,592,000 seconds) — suitable for content-hashed filenames.
     *
     * @param  string  $path  Path relative to the public storage disk
     */
    public function serveWithCacheHeaders(string $path): \Illuminate\Http\Response
    {
        $disk = Storage::disk('public');

        abort_if(! $disk->exists($path), 404, 'Image not found.');

        return response($disk->get($path), 200, [
            'Content-Type' => $disk->mimeType($path) ?? 'image/webp',
            'Cache-Control' => 'public, max-age=2592000, immutable',
            'Last-Modified' => gmdate('D, d M Y H:i:s', $disk->lastModified($path)) . ' GMT',
        ]);
    }
}
