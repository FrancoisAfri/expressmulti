<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;

trait PdfGenaratorTrait
{
    /*
     *
     */
    public function pdfGenarator($view){
        return PDF::loadView($view)
            ->setPaper(array(0, 0, 609.4488, 935.433), 'landscape');
    }
}
