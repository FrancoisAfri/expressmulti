<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeDoctor extends Model
{
    use HasFactory;

    protected $table = 'practice_doctors';

    protected $fillable = [
        'name', 'practice_number', 'speciality', 'status',
    ];

    public static function getDoctor()
    {
        return PracticeDoctor::get();
    }
}
