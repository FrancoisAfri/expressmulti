<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use HasFactory;

    protected $table = 'modifier';

    protected $fillable = [
        'code', 'procedure_code', 'rules',
        'percentage', 'status',
    ];



    public function procedureCode()
    {
        return $this->belongsTo(ProcedureCode::class,'procedure_code','id');
    }

    public static function getModifier()
    {
        return Modifier::with('procedureCode')->get();
    }



    public static function getModifierPercentageById($id)
    {
        if ($id < 1) {
            return Modifier::where('id', 1)->with('procedureCode')->first();
        } else
            return Modifier::where('id', $id)->with('procedureCode')->first();
    }
}
