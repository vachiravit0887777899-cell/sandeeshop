<?php

namespace App\Services;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;

class CloudinaryUploadService
{
    public static function upload(UploadedFile $file, string $folder): string
    {
        Configuration::instance(env('CLOUDINARY_URL'));

        $result = (new UploadApi())->upload($file->getRealPath(), [
            'folder' => $folder,
        ]);

        return $result['secure_url'];
    }
}