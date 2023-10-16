<?php
/**
 * Store Image trait
 * 31 December 2022
 * Nkosana Gift
 * ncubesss@gmail.com
 */

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUpload
{
    public function uploadFile
    (
        Request $request,
                $file,
                $directory,
                $moduleName

    )
    {
        if ($request->hasFile($file)) {
            $file_name = $request->file($file);
            $File_ext = $file_name->extension();

            if (in_array($File_ext, ['jpg', 'png', 'jpeg', 'png', 'gif', 'doc', 'docx',
                    'pdf', 'xls', 'xlsx', 'txt', 'lic', 'xml', 'zip', 'rtf', 'rar']) &&
                $file_name->isValid()) {

                $file_basename = time();
                $filename = pathinfo($file_name->getClientOriginalName(), PATHINFO_FILENAME);

//                $small = Image::make($request->file($file))->resize(200, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                })->stream();
//
//                $medium = Image::make($request->file($file))->resize(800, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                })->stream();
//
//                $large = Image::make($request->file($file))->resize(1000, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                })->stream();


                $fileNameToStore = 'small' . $filename . '-' . Str::random(8) . '.' . $File_ext;

                Storage::disk('public')->put($directory . $fileNameToStore, 'small', 'public');

                $moduleName->$file = $fileNameToStore;
                return $moduleName->update();
            }
        }
        return null;
    }


    public function uploadImage
    (
        Request $request,
                $file,
                $directory,
                $moduleName
    )
    {
        if ($request->hasFile($file)) {
            $file_name = $request->file($file);
            $File_ext = $file_name->extension();
            if (in_array($File_ext, ['jpg', 'png', 'jpeg', 'png', 'gif', 'doc', 'docx',
                    'pdf', 'xls', 'xlsx', 'txt', 'lic', 'xml', 'zip', 'rtf', 'rar']) &&
                $file_name->isValid()) {
                $filename = pathinfo($file_name->getClientOriginalName(), PATHINFO_FILENAME);
                $fileNameToStore =  $filename . '-' . Str::random(8) . '.' . $File_ext;
                $nameFile  = $file_name->store('uploads');

                $fileName = substr($nameFile, strpos($nameFile, '/') + 1);
                $moduleName->$file = $fileName;
                return $moduleName->update();
            }
        }
        return null;
    }
}
