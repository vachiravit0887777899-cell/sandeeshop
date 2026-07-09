<?php

if (! function_exists('image_url')) {
    function image_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        // ถ้าเป็น URL เต็มอยู่แล้ว (มาจาก Cloudinary) ใช้ตรงๆ ได้เลย
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // ถ้าเป็น path เก่าแบบ local storage (ก่อนย้ายมา Cloudinary) ให้ fallback แบบเดิม
        return \Storage::url($path);
    }
}