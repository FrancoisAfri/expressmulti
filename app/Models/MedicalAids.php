<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalAids extends Model
{
    use HasFactory;

    protected $table = 'medical_aids';

    protected $fillable = [
        'name', 'email', 'short_code', 'long_code',
        'switching_code', 'administrator', 'telephone',
        'status',
    ];
	// relationship between medical aids and invoices
	public function invoices(){
        return $this->hasMany(BillingInvoice::class);
    }

    public static function getMedicalAids()
    {
        return MedicalAids::get();
    }

    public static function getMedicalAidsBYiD($id)
    {
        return MedicalAids::select('name')
            ->where('id', $id)
            ->first();
    }

}
