<?php

namespace App\Utils;

class ImageUpload
{
    public static function upload($file, $folder)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        return 'storage/' . $file->storeAs($folder, $filename, 'public');
    }
}
