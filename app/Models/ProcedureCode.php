<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedureCode extends Model
{
    use HasFactory;

    protected $table = 'procedure_code';

    protected $fillable = [
        'code', 'price', 'description', 'status',
    ];

    public static function getProcedureCode()
    {
        return ProcedureCode::get();
    }

    public static function getProcedurePrice($id)
    {
        if ($id < 1) {
            return ProcedureCode::where('id', 1)->first();
        } else
            return ProcedureCode::where('id', $id)->first();
    }
}
