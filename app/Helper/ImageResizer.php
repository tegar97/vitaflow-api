<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;


use Image;

class ImageResizer
{

    public static function ResizeImage($file, $folderName, $imageFor, $width = 80, $height = 80, $type = "png", $quality = 90)
    {

        // Resize image
        $resize = Image::make($file)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->encode($type, $quality);

        // Create hash value
        $hash = md5($resize->__toString());
        $var = date_create();
        $time = date_format($var, 'YmdHis');
        $imageName = $time . '-' . $imageFor . '.' . $type;

        // Put image to storage
        $save = Storage::disk("oss")->put("images/$folderName/{$imageName}", $resize);

        if ($save) {
            return $imageName;
        }
        return false;
    }
}
