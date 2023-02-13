<?php
namespace App\Helpers;

use Auth;
use Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Helpers {

    public static function getImg($folder = null, $filename = null, $image_type='non_user', $disk='storage')
    {
        $img_path = $disk . $folder . $filename;

        // dd($img_path,public_path($img_path),file_exists(public_path($img_path)));
        if($filename != null && $filename != '' && file_exists(public_path($img_path)))
            return asset($img_path);
        else
            return ($image_type == 'non_user') ? asset(config('globals.DEFAULT_IMAGE_PATH')) : asset(config('globals.DEFAULT_USER_IMAGE_PATH'));
    }

}