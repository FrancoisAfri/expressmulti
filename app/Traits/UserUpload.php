<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;

trait UserUpload
{
    public function saveAndResize($inputName , $request) {
        $file_basename = time();
        $file_extension = $request->$inputName->extension();

        $user_id = '234567890'; // test purposes
        $filepath = 'user/' . $user_id . '/uploads/';

        $small = Image::make( $request->$inputName )->resize(200, null, function($constraint){
            $constraint->aspectRatio();
        })->stream();
        $medium = Image::make( $request->$inputName )->resize(800, null, function($constraint){
            $constraint->aspectRatio();
        })->stream();
        $large = Image::make( $request->$inputName )->resize(1000, null, function($constraint){
            $constraint->aspectRatio();
        })->stream();

        Storage::disk('public')->put( $filepath.$file_basename.'_small.'.$file_extension, $small, 'public');
        Storage::disk('public')->put( $filepath.$file_basename.'_medium.'.$file_extension, $medium, 'public');
    }
}
