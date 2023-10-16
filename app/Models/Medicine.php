<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $table = 'medicine';

    protected $fillable = [
        'name', 'code', 'status',
    ];

    public function getMedicine()
    {
        return Medicine::all();
    }
}
