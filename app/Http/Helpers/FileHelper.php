<?php

namespace App\Http\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function uploadToDocs(UploadedFile $file, $filePath = '',$filename=null)
    {
        if (empty($file)) return "";
        if (!file_exists($filePath)) {
            // mkdir($filePath, 0777, true);
            Storage::makeDirectory($filePath);
        }
        $fileExtension = $file->getClientOriginalExtension();
        $generated_name = time() . ".$fileExtension";
        $filename = $filename ?? $generated_name;
        return $file->storeAs($filePath, $filename);
    }
}
