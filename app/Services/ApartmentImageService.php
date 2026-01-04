<?php

namespace App\Services;

use App\Models\ApartmentImage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;

class ApartmentImageService
{
    public function storeFromPaths(
        int $apartmentId,
        array $paths,
        ?string $altText = null
    ): void {
        $hasFeatured = ApartmentImage::where('apartment_id', $apartmentId)
            ->where('is_featured', true)
            ->exists();

        foreach ($paths as $index => $path) {
            $fullPath = Storage::disk('public')->path($path);

            $result = Cloudinary::uploadApi()->upload($fullPath, [
                'folder' => 'apartments',
            ]);

            ApartmentImage::create([
                'apartment_id' => $apartmentId,
                'image_path'   => $result['public_id'],
                'alt_text'     => $altText,
                'is_featured'  => ! $hasFeatured && $index === 0,
                'sort_order'   => ApartmentImage::where('apartment_id', $apartmentId)->max('sort_order') + 1,
            ]);

            Storage::disk('public')->delete($path);
        }
    }
}
