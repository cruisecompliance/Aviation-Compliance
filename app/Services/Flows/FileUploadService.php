<?php

namespace App\Services\Flows;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    public function upload(UploadedFile $file, string $folder): string
    {

        // create new file name
        // $fileName =  time() . '_' . $folder  . '.' . $file->getClientOriginalExtension();
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        // store file on disk
         $path = Storage::disk('public')->putFileAs($folder, $file, $fileName);

        // return file name
        return $fileName;
    }

}
