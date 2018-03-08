<?php

namespace App\Helper;

class Helper
{
    public static function upload($file, $upTo)
    {
        $fileName = time() . '_' . rand (10000000, 99999999) . '.' . $file->getClientOriginalExtension();
        $file->move(config('settings.path_upload') . $upTo . '/', $fileName);

        return config('settings.path_image') . $upTo . '/' . $fileName;
    }
}