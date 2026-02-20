<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToStorage extends Command
{
    protected $signature = 'app:migrate-images';

    protected $description = 'Migrate existing images from public/img to storage/app/public and update database paths';

    public function handle(): int
    {
        $this->info('Starting image migration to Laravel Storage...');

        $productCount = $this->migrateFiles('img/products', 'products');
        $galleryCount = $this->migrateFiles('img/products/gallery', 'products/gallery');
        $categoryCount = $this->migrateFiles('img/categories', 'categories');

        // Update product image_url paths in database
        $updatedProducts = Product::withTrashed()
            ->where('image_url', 'like', 'img/products/%')
            ->where('image_url', 'not like', 'img/products/gallery/%')
            ->get();

        foreach ($updatedProducts as $product) {
            $oldPath = $product->getRawOriginal('image_url');
            $newPath = preg_replace('/^img\/products\//', 'products/', $oldPath);
            $product->update(['image_url' => $newPath]);
        }

        $this->info("Updated {$updatedProducts->count()} product image paths in DB.");

        // Update product gallery image paths
        $updatedGallery = ProductImage::where('image_path', 'like', 'img/products/gallery/%')->get();

        foreach ($updatedGallery as $image) {
            $oldPath = $image->getRawOriginal('image_path');
            $newPath = preg_replace('/^img\/products\/gallery\//', 'products/gallery/', $oldPath);
            $image->update(['image_path' => $newPath]);
        }

        $this->info("Updated {$updatedGallery->count()} gallery image paths in DB.");

        // Update category image_url paths
        $updatedCategories = Category::where('image_url', 'like', 'img/categories/%')->get();

        foreach ($updatedCategories as $category) {
            $oldPath = $category->getRawOriginal('image_url');
            $newPath = preg_replace('/^img\/categories\//', 'categories/', $oldPath);
            $category->update(['image_url' => $newPath]);
        }

        $this->info("Updated {$updatedCategories->count()} category image paths in DB.");

        $this->newLine();
        $this->info('Migration complete!');
        $this->table(
            ['Type', 'Files Copied', 'DB Records Updated'],
            [
                ['Products', $productCount, $updatedProducts->count()],
                ['Gallery', $galleryCount, $updatedGallery->count()],
                ['Categories', $categoryCount, $updatedCategories->count()],
            ]
        );

        $this->newLine();
        $this->comment('Original files in public/img/ are preserved. You can remove them manually after verifying.');

        return self::SUCCESS;
    }

    private function migrateFiles(string $sourceDir, string $targetDir): int
    {
        $sourcePath = public_path($sourceDir);

        if (! File::isDirectory($sourcePath)) {
            $this->warn("Source directory not found: {$sourceDir}");

            return 0;
        }

        $files = File::files($sourcePath);
        $count = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $targetPath = $targetDir.'/'.$filename;

            if (! Storage::disk('public')->exists($targetPath)) {
                Storage::disk('public')->put($targetPath, File::get($file->getPathname()));
                $count++;
            }
        }

        $this->info("Copied {$count} files from {$sourceDir} to storage/{$targetDir}");

        return $count;
    }
}
