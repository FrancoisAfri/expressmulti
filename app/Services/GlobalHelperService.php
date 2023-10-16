<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class GlobalHelperService
{

    /**
     * @return Collection
     */
    public static function getAvailableModels(): Collection
    {

        $models = collect();
        $modelsPath = app_path('Models');
        $modelFiles = File::allFiles($modelsPath);
        foreach ($modelFiles as $modelFile) {
            $models->add('App\\' .'Models\\' . $modelFile->getFilenameWithoutExtension());
        }

        return $models;
    }

    public static function khdjhd(){
        activity()->log('$description')->causedBy($user);
    }

}
