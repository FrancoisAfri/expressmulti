<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icd10_codes extends Model
{
    use HasFactory;

    protected $table = 'icd10_codes';


    protected $fillable = [
        'name'
    ];

    public static function getAllCodes(){
        return Icd10_codes::all();
    }
}
