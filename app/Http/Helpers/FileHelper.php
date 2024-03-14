<?php

namespace App\Http\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function uploadToDocs(UploadedFile $file, $filePath = '')
    {
        if (empty($file)) return "";
        if (!file_exists($filePath)) {
            // mkdir($filePath, 0777, true);
            Storage::makeDirectory($filePath);
        }
        $filename = $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();
        $generated_name = time() . ".$fileExtension";
        return $file->storeAs($filePath, $generated_name);
    }
}
