<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudinaryService
{
    public function uploadInvoice(string $path): string
    {
        $uploaded = Cloudinary::upload($path, [
            'folder' => 'invoices',
            'resource_type' => 'raw'
        ]);

        return $uploaded->getSecurePath();
    }
}
