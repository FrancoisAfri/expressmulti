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
				$nameFile = Storage::disk('public')->put('Images/' . $fileNameToStore,
				file_get_contents($request->file($file)));
				//$nameFile = $file_name->storeAs($directory, $fileNameToStore);
                $fileName = substr($nameFile, strpos($nameFile, '/') + 1);
                $moduleName->$file = $fileName;
                return $moduleName->update();
            }
        }
        return null;
    }
	// upload video
	public function uploadVideo(Request $request,$file,$directory,$moduleName)
    {
        if ($request->hasFile($file)) {
            $file_name = $request->file($file);
            $File_ext = $file_name->extension();
            if (in_array($File_ext, ['mp4']) && $file_name->isValid()) 
			{
				
                //$filename = pathinfo($file_name->getClientOriginalName(), PATHINFO_FILENAME);
                //$fileNameToStore =  $filename . '-' . Str::random(8) . '.' . $File_ext;
               // $nameFile  = $file_name->store('uploads');

                //$fileName = substr($nameFile, strpos($nameFile, '/') + 1);
				// 
				$fileName = time().'_'.$request->$file->getClientOriginalName();
				//$file_path = $request->file('video')->storeAs('Videos', $file_name);
				
				//$filePath = 'emp_vid' . ' ' . str_random(16) . '.' . $File_ex;
				//$isFileUploaded = Storage::disk('public')->put('Videos/' . $filePath,file_get_contents($request->file('video')));
				//
				$filePath = 'res_vid' . '-' . str_random(16) . '.' . $File_ext;
				$isFileUploaded = Storage::disk('public')->put('Videos/' . $filePath,
				file_get_contents($request->file($file)));
			
                $moduleName->$file = $filePath;
                return $moduleName->update();
            }
        }
        return null;
    }
}
