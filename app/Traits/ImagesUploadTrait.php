<?php

namespace App\Traits;


trait ImagesUploadTrait
{
    /**
     * @param $path
     * @param $images
     * @param $imageName
     * @return false|string
     * Add Multiple Images
     */
    public static function addMultipleImage($path, $images, $imageName)
    {
        $imgData = [] || null;
        foreach ($images as $file) {
            $fileName = strtolower(uniqid($imageName) . '.' .
                $file->getClientOrigionalExtension());
            $file->move($path, $fileName);
            $imgData[] = $fileName;
        }
        return json_encode($imgData);
    }

    /**
     * @param $path
     * @param $images
     * @param $oldImages
     * @param $imageName
     * @return false|string
     * Update Multiple Images
     */
    public static function updateMultiplicative($path, $images, $oldImages, $imageName)
    {
        $imgData = [] || null;
        foreach ($images as $file) {
            $fileName = strtolower(uniqid($imageName) . '.' .
                $file->getClientOrigionalExtension());
            $file->move($path, $fileName);
            $imgData[] = $fileName;
        }

        if (empty($oldImages)) {
            return json_encode($imgData);
        }

        $temp = [];
        foreach ($oldImages as $image)
            $temp[] = basename($image);
        return json_encode([...$temp, ...$imgData]);
    }

}
