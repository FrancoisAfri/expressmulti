<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceHouse extends Model
{
    use HasFactory;



    protected $table = 'insuarance';

    protected $fillable = [
        'name', 'email', 'address',
        'contact_number', 'status'
    ];

    public static function getInsuranceHouse()
    {
        return InsuranceHouse::get();
    }

    public static function getInsuaranceDetailsById($id){
        return InsuranceHouse::where('id',$id)->first();
    }
}
