<?php

namespace App\Http\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class FileHelper
{
    public static function uploadToDocs(UploadedFile $file, $filePath = '', $filename = null)
    {
        if (empty($file)) return "";
        $folder_container = explode('/',$filePath);
        if (!Storage::directoryExists($folder_container)) {
            // mkdir($filePath, 0777, true);
            Storage::makeDirectory($folder_container);
        } else {
            Storage::deleteDirectory($folder_container);
            Storage::makeDirectory($filePath);
        }
        $fileExtension = $file->getClientOriginalExtension();
        $generated_name = time() . ".$fileExtension";
        $filename = $filename ?? $generated_name;
        return $file->storeAs($filePath, $filename);
    }
    public static function uploadBase64ToDocs(string $base64, string $filename)
    {
        $binaryData = base64_decode($base64);
        $filePath = storage_path('app/public/media/') . $filename;
        Storage::put($filePath, $binaryData);
        return $filePath;
    }
}
