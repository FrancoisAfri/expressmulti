<?php
/**
 * BreadCramp trait
 * 31 August 2022
 * Nkosana Gift
 * ncubesss@gmail.com
 */

namespace App\Traits;

use Illuminate\Http\Request;

trait BreadCrumpTrait
{

    /**
     * @param $pageTitle
     * @param $pageDescription
     * @param $title1
     * @param $title2
     * @param $path
     * @return array
     */
    public function breadcrumb
    (

        $pageTitle,
        $pageDescription,
        $path,
        $title1,
        $title2
    ): array
    {
        $data['page_title'] = $pageTitle;
        $data['page_description'] = $pageDescription;

        $data['breadcrumb'] = [
            ['title' => $title1, 'path' => $path],
            ['title' => $title2, 'active' => 1, 'is_module' => 0]
        ];

        return $data;
    }

}
